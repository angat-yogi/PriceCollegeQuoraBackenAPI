<?php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;


require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../middleware/BeforeMiddleware.php';
require_once __DIR__ . '/../middleware/AfterMiddleware.php';

$app = AppFactory::create();
$app->setBasePath('/pricecollegequora/public');

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    $payload = array();
    $payload['status']=$exception->getCode();
    $payload['message']=$exception->getMessage();



    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
      json_encode($payload)
    );

    return $response->withHeader('Content-Type','application/json')->withStatus($exception->getCode()!=0?$exception->getCode():500);
};

/**
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->add(new BeforeMiddleware());
$app->add(new AfterMiddleware());

require_once __DIR__ ."/../app/user.php";
require_once __DIR__ ."/../app/utils.php";
require_once __DIR__ ."/../app/post.php";
require_once __DIR__ ."/../app/friend.php";
require_once __DIR__ ."/../app/comment.php";




// Run app
$app->run();