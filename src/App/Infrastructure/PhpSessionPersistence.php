<?php

namespace App\Infrastructure;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Session\Session;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Session\SessionPersistenceInterface;
use Zend\Session\SaveHandler\DbTableGateway;

/**
 * Session persistence using ext-session.
 *
 * Adapts ext-session to work with PSR-7 by disabling its auto-cookie creation
 * (`use_cookies => false`), while simultaneously requiring cookies for session
 * handling (`use_only_cookies => true`). The implementation pulls cookies
 * manually from the request, and injects a `Set-Cookie` header into the
 * response.
 *
 * Session identifiers are generated using random_bytes (and casting to hex).
 * During persistence, if the session regeneration flag is true, a new session
 * identifier is created, and the session re-started.
 */
class PhpSessionPersistence implements SessionPersistenceInterface
{
    /**
     * @var DbTableGateway
     */
    private $saveHandler;

    public function __construct(DbTableGateway $saveHandler)
    {
        $this->saveHandler = $saveHandler;
    }

    public function initializeSessionFromRequest(ServerRequestInterface $request) : SessionInterface
    {
        session_set_save_handler($this->saveHandler);
        register_shutdown_function([$this, 'writeClose']);

        $id = FigRequestCookies::get($request, session_name())->getValue() ?: $this->generateSessionId();
        $this->startSession($id);

        return new Session($_SESSION);
    }

    public function persistSession(SessionInterface $session, ResponseInterface $response) : ResponseInterface
    {
        if ($session->isRegenerated()) {
            $this->regenerateSession();
        }

        $_SESSION = $session->toArray();

        $sessionCookie = SetCookie::create(session_name())
            ->withValue(session_id())
            ->withPath(ini_get('session.cookie_path'));

        return FigResponseCookies::set($response, $sessionCookie);
    }

    public function writeClose()
    {
        session_write_close();
    }

    private function startSession(string $id) : void
    {
        session_id($id);
        session_start([
            'use_cookies'      => false,
            'use_only_cookies' => true,
        ]);
    }

    /**
     * Regenerates the session safely.
     *
     * @link http://php.net/manual/en/function.session-regenerate-id.php (Example #2)
     */
    private function regenerateSession() : void
    {
        session_commit();
        ini_set('session.use_strict_mode', 0);
        $this->startSession($this->generateSessionId());
    }

    /**
     * Generate a session identifier.
     */
    private function generateSessionId() : string
    {
        return bin2hex(random_bytes(16));
    }
}
