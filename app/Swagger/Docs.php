<?php

namespace App\Swagger;
/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Komun",
 *     description="API REST para la plataforma Komun - Conectando personas que necesitan ayuda con asistentes",
 *     @OA\Contact(
 *         email="soporte@komun.com",
 *         name="Soporte Komun"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor de API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class Docs
{

}
