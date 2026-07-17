<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
    /*
     * The default class to use for all routes
     *
     * The following route classes are supplied with CakePHP and are appropriate
     * to set as the default:
     *
     * - Route
     * - InflectedRoute
     * - DashedRoute
     *
     * If no call is made to `Router::defaultRouteClass()`, the class used is
     * `Route` (`Cake\Routing\Route\Route`)
     *
     * Note that `Route` does not do any inflections on URLs which will result in
     * inconsistently cased URLs when used with `{plugin}`, `{controller}` and
     * `{action}` markers.
     */
    $routes->setRouteClass(DashedRoute::class);
    $routes->connect('/admin/teachers', ['controller'=>'Admin','action'=>'teachers']);
    $routes->connect('/admin/addTeacher', ['controller'=>'Admin','action'=>'addTeacher']);
    $routes->connect('/admin/editTeacher/{id}', ['controller'=>'Admin','action'=>'editTeacher'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/deleteTeacher/{id}', ['controller'=>'Admin','action'=>'deleteTeacher'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/teacher-availability', ['controller' => 'Admin', 'action' => 'teacherAvailabilitySlots']);
    $routes->connect('/admin/deleteTeacherAvailabilitySlot/:id', ['controller' => 'Admin', 'action' => 'deleteTeacherAvailabilitySlot'])
        ->setPass(['id']);
        $routes->connect('/booking', [
    'controller' => 'Bookings',
    'action' => 'add'
]);
    // Booking API endpoints - short URLs to avoid Apache routing issues on production
    $routes->connect('/bookings/slots', [
        'controller' => 'Bookings',
        'action' => 'getAvailableSlots',
    ]);

    $routes->connect('/bookings/seats', [
        'controller' => 'Bookings',
        'action' => 'getAvailableSeats',
    ]);

    // Booking lifecycle pages (Stripe redirects to these URLs)
    // Use glob pattern (*) to reliably pass any value including UUIDs with dashes
    $routes->connect('/bookings/success/*', [
        'controller' => 'Bookings',
        'action' => 'success',
    ]);

    $routes->connect('/bookings/cancel-payment/*', [
        'controller' => 'Bookings',
        'action' => 'cancelPayment',
    ]);

    $routes->connect('/bookings/cancelPayment/*', [
        'controller' => 'Bookings',
        'action' => 'cancelPayment',
    ]);

    $routes->connect('/bookings/pay-again/*', [
        'controller' => 'Bookings',
        'action' => 'payAgain',
    ]);

    $routes->connect('/bookings/payAgain/*', [
        'controller' => 'Bookings',
        'action' => 'payAgain',
    ]);

    $routes->connect('/bookings/pay-group/*', [
        'controller' => 'Bookings',
        'action' => 'payGroup',
    ]);

    $routes->connect('/bookings/payGroup/*', [
        'controller' => 'Bookings',
        'action' => 'payGroup',
    ]);

    $routes->connect('/bookings/cancel/*', [
        'controller' => 'Bookings',
        'action' => 'cancel',
    ]);

    // Legacy dashed URLs (keep for backward compatibility)
    $routes->connect('/bookings/get-available-slots/{workshopId}', [
        'controller' => 'Bookings',
        'action' => 'getAvailableSlots',
    ])->setPass(['workshopId'])->setPatterns(['workshopId' => '\d+']);

    $routes->connect('/bookings/get-available-seats/{workshopId}/{bookingDate}', [
        'controller' => 'Bookings',
        'action' => 'getAvailableSeats',
    ])->setPass(['workshopId', 'bookingDate']);

    $routes->connect('/bookings/get-available-seats/{workshopId}', [
        'controller' => 'Bookings',
        'action' => 'getAvailableSeats',
    ])->setPass(['workshopId'])->setPatterns(['workshopId' => '\d+']);
    $routes->connect('/stripe/webhook', ['controller' => 'Stripe', 'action' => 'webhook']);
    $routes->connect('/admin', ['controller' => 'Admin', 'action' => 'index']);
    $routes->connect('/admin/enquiries', ['controller' => 'Admin', 'action' => 'enquiries']);
    $routes->connect('/admin/users', ['controller' => 'Admin', 'action' => 'users']);
    $routes->connect('/admin/addUser', ['controller' => 'Admin', 'action' => 'addUser']);
    $routes->connect('/admin/add', ['controller' => 'Admin', 'action' => 'addUser']);
    $routes->connect('/admin/deleteUser/*', ['controller' => 'Admin', 'action' => 'deleteUser']);
    $routes->connect('/admin/deleteEnquiry/*', ['controller' => 'Admin', 'action' => 'deleteEnquiry']);
    $routes->connect('/admin/workshops', ['controller' => 'Admin', 'action' => 'workshops']);
    $routes->connect('/admin/addWorkshop', ['controller' => 'Admin', 'action' => 'addWorkshop']);
    $routes->connect('/admin/editWorkshop/{id}', ['controller' => 'Admin', 'action' => 'editWorkshop'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/deleteWorkshop/{id}', ['controller' => 'Admin', 'action' => 'deleteWorkshop'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/materials', ['controller' => 'Admin', 'action' => 'materials']);
    $routes->connect('/admin/materials/add', ['controller' => 'Admin', 'action' => 'addMaterial']);
    $routes->connect('/admin/materials/edit/*', ['controller' => 'Admin', 'action' => 'editMaterial']);
    $routes->connect('/admin/materials/delete/*', ['controller' => 'Admin', 'action' => 'deleteMaterial']);
    $routes->connect('/admin/bookings', ['controller' => 'Admin', 'action' => 'bookings']);
    $routes->connect('/admin/faqs', ['controller' => 'Admin', 'action' => 'faqs']);
    $routes->connect('/admin/faqs/add', ['controller' => 'Admin', 'action' => 'addFaq']);
    $routes->connect('/admin/faqs/edit/{id}', ['controller' => 'Admin', 'action' => 'editFaq'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/faqs/delete/{id}', ['controller' => 'Admin', 'action' => 'deleteFaq'])
        ->setPass(['id'])->setPatterns(['id' => '\d+']);
    $routes->connect('/admin/company', ['controller' => 'Admin', 'action' => 'company']);
    $routes->scope('/', function (RouteBuilder $builder): void {
        /*
         * Here, we are connecting '/' (base path) to a controller called 'Pages',
         * its action called 'display', and we pass a param to select the view file
         * to use (in this case, templates/Pages/home.php)...
         */
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/register', [
            'controller' => 'Users',
            'action' => 'register'
        ]);

        $builder->connect('/pages/login', [
            'controller' => 'Users',
            'action' => 'login'
        ]);
        $builder->connect('/teacher', ['controller' => 'Teacher', 'action' => 'index']);
        $builder->connect('/teacher/profile', ['controller' => 'Teacher', 'action' => 'profile']);
        $builder->connect('/teacher/availability', ['controller' => 'Teacher', 'action' => 'availability']);
        $builder->connect('/teacher/availability/save', ['controller' => 'Teacher', 'action' => 'saveAvailability']);
        $builder->connect('/teacher/workshops', ['controller' => 'Teacher', 'action' => 'workshops']);
        $builder->connect('/teacher/workshops/add', ['controller' => 'Teacher', 'action' => 'addWorkshop']);
        $builder->connect('/teacher/workshops/edit/{id}', ['controller' => 'Teacher', 'action' => 'editWorkshop'])
            ->setPass(['id']);
        $builder->connect('/teacher/students', ['controller' => 'Teacher', 'action' => 'students']);
        $builder->connect('/teacher/students/view/{id}', ['controller' => 'Teacher', 'action' => 'viewStudent'])
            ->setPass(['id']);
        $builder->connect('/teacher/attendance', ['controller' => 'Teacher', 'action' => 'attendance']);
        $builder->connect('/teacher/attendance/save', ['controller' => 'Teacher', 'action' => 'saveAttendance']);
        $builder->connect('/teacher/messages', ['controller' => 'Teacher', 'action' => 'messages']);
        $builder->connect('/teacher/messages/send', ['controller' => 'Teacher', 'action' => 'sendMessage']);
        $builder->connect('/teacher/earnings', ['controller' => 'Teacher', 'action' => 'earnings']);
        $builder->connect('/teacher/reports/download', ['controller' => 'Teacher', 'action' => 'downloadReport']);
        
        // Teacher Slot Management Routes
        $builder->connect('/teacher/slots', ['controller' => 'Teacher', 'action' => 'slots']);
        $builder->connect('/teacher/slots/create', ['controller' => 'Teacher', 'action' => 'createSlot']);
        $builder->connect('/teacher/slots/edit/{id}', ['controller' => 'Teacher', 'action' => 'editSlot'])
            ->setPass(['id']);
        $builder->connect('/teacher/slots/cancel/{id}', ['controller' => 'Teacher', 'action' => 'cancelSlot'])
            ->setPass(['id']);
        $builder->connect('/teacher/calendar', ['controller' => 'Teacher', 'action' => 'calendar']);
        $builder->connect('/teacher/attendance/{slotId}', ['controller' => 'Teacher', 'action' => 'attendance'])
            ->setPass(['slotId']);
        $builder->connect('/teacher/attendance/{slotId}/lock', ['controller' => 'Teacher', 'action' => 'lockAttendance'])
            ->setPass(['slotId']);

        $builder->connect('/faqs', [
            'controller' => 'Pages',
            'action' => 'display',
            'faqs'
        ]);
        $builder->connect('/workshops', [
            'controller' => 'Pages',
            'action' => 'display',
            'workshops'
        ]);
        $builder->connect('/visit', [
            'controller' => 'Pages',
            'action' => 'display',
            'visit',
        ]);

        $builder->connect('/contact', [
            'controller' => 'Pages',
            'action' => 'display',
            'contact',
        ])->setMethods(['GET']);

        $builder->connect('/contact', [
            'controller' => 'Enquiries',
            'action' => 'add',
        ])->setMethods(['POST']);

        /*
         * ...and connect the rest of 'Pages' controller's URLs.
         */
        $builder->connect('/pages/*', 'Pages::display');

        // Enquiries route
        $builder->connect('/enquiries/add', ['controller' => 'Enquiries', 'action' => 'add']);

        // Materials CRUD routes
        $builder->connect('/materials', ['controller' => 'Materials', 'action' => 'index']);
        $builder->connect('/materials/add', ['controller' => 'Materials', 'action' => 'add']);
        $builder->connect('/materials/edit/{id}', ['controller' => 'Materials', 'action' => 'edit'])
            ->setPass(['id']);
        $builder->connect('/materials/delete/{id}', ['controller' => 'Materials', 'action' => 'delete'])
            ->setPass(['id']);

        /*
         * Connect catchall routes for all controllers.
         *
         * The `fallbacks` method is a shortcut for
         *
         * ```
         * $builder->connect('/{controller}', ['action' => 'index']);
         * $builder->connect('/{controller}/{action}/*', []);
         * ```
         *
         * It is NOT recommended to use fallback routes after your initial prototyping phase!
         * See https://book.cakephp.org/5/en/development/routing.html#fallbacks-method for more information
         */
        $builder->fallbacks();
    });

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};
