<?php
/**
 * Этот файл подключается в composer.json в секции autoload:
 *
 * "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ],
        "files": [
            "app/Providers/helpers.php"
        ]
    },
 */
if (! function_exists('user')) {
    function user()
    {
        /**
         * @var $user \App\Models\User
         */
        $user = auth()->user();

        return $user;
    }
}

function set_active_by_route($currentRouteName, $routeOrRoutes, $classForActive = 'active', $classForNonactive = '')
{
    //$currentRouteName = Route::currentRouteName();
    if (! is_array($routeOrRoutes)) {
        $routesExpected[] = $routeOrRoutes;
    } else {
        $routesExpected = $routeOrRoutes;
    }
    foreach ($routesExpected as $routeExpected) {
        if (strpos($routeExpected, '*') !== false) {
            // проверяем вхождение подстроки
            if (strpos($routeExpected, '*') !== 0) {
                // route*
                $routeExpected = str_replace('*', '', $routeExpected);
                if (strpos($currentRouteName, $routeExpected) === 0) {
                    return $classForActive;
                } else {
                    return $classForNonactive;
                }
            } else {
                // *route - но фактически ищетcя *route*
                $routeExpected = str_replace('*', '', $routeExpected);
                if (strpos($currentRouteName, $routeExpected) != 0) {
                    return $classForActive;
                } else {
                    return $classForNonactive;
                }
            }
        } else {
            // проверяем точное cоответствие
            if ($currentRouteName === $routeExpected) {
                return $classForActive;
            } else {
                return $classForNonactive;
            }
        }
    }
}

function displayNikayaTitleByCategory($suttaCategory)
{
    switch (strtolower($suttaCategory)) {
        case 'dn':
            return 'Дигха Никая';
        case 'mn':
            return 'Мадджима Никая';
        case 'an':
            return 'Ангуттара Никая';
        case 'sn':
            return 'Саньютта Никая';
        default:
            return 'неизвестно';
    }
}
