<?php return [
    'services' =>
        [
        ],
    'aliases'  =>
        [
        ],
    'provided' =>
        [
            'Valkyrja\\Contracts\\Annotations\\AnnotationsParser'               => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Annotations\\Annotations'                     => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Container\\Annotations\\ContainerAnnotations' => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Events\\Annotations\\ListenerAnnotations'     => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Console\\Annotations\\CommandAnnotations'     => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Routing\\Annotations\\RouteAnnotations'       => 'Valkyrja\\Annotations\\Providers\\AnnotationsServiceProvider',
            'Valkyrja\\Contracts\\Http\\Client'                                 => 'Valkyrja\\Http\\Providers\\ClientServiceProvider',
            'Valkyrja\\Contracts\\Console\\Console'                             => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
            'Valkyrja\\Contracts\\Console\\Kernel'                              => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
            'Valkyrja\\Contracts\\Console\\Input\\Input'                        => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
            'Valkyrja\\Contracts\\Console\\Output\\OutputFormatter'             => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
            'Valkyrja\\Contracts\\Console\\Output\\Output'                      => 'Valkyrja\\Console\\Providers\\ConsoleServiceProvider',
            'Valkyrja\\Contracts\\Filesystem\\Filesystem'                       => 'Valkyrja\\Filesystem\\Providers\\FilesystemServiceProvider',
            'Valkyrja\\Contracts\\Http\\Kernel'                                 => 'Valkyrja\\Http\\Providers\\HttpServiceProvider',
            'Valkyrja\\Contracts\\Http\\Request'                                => 'Valkyrja\\Http\\Providers\\HttpServiceProvider',
            'Valkyrja\\Contracts\\Http\\Response'                               => 'Valkyrja\\Http\\Providers\\HttpServiceProvider',
            'Valkyrja\\Contracts\\Http\\JsonResponse'                           => 'Valkyrja\\Http\\Providers\\JsonResponseServiceProvider',
            'Monolog\\Handler\\StreamHandler'                                   => 'Valkyrja\\Logger\\Providers\\LoggerServiceProvider',
            'Psr\\Log\\LoggerInterface'                                         => 'Valkyrja\\Logger\\Providers\\LoggerServiceProvider',
            'Valkyrja\\Contracts\\Logger\\Logger'                               => 'Valkyrja\\Logger\\Providers\\LoggerServiceProvider',
            'Valkyrja\\Contracts\\Path\\PathGenerator'                          => 'Valkyrja\\Path\\Providers\\PathServiceProvider',
            'Valkyrja\\Contracts\\Path\\PathParser'                             => 'Valkyrja\\Path\\Providers\\PathServiceProvider',
            'Valkyrja\\Contracts\\Http\\RedirectResponse'                       => 'Valkyrja\\Http\\Providers\\RedirectResponseServiceProvider',
            'Valkyrja\\Contracts\\Http\\ResponseBuilder'                        => 'Valkyrja\\Http\\Providers\\ResponseBuilderServiceProvider',
            'Valkyrja\\Contracts\\Routing\\Router'                              => 'Valkyrja\\Routing\\Providers\\RoutingServiceProvider',
            'Valkyrja\\Contracts\\Session\\Session'                             => 'Valkyrja\\Session\\Providers\\SessionServiceProvider',
            'Valkyrja\\Contracts\\View\\View'                                   => 'Valkyrja\\View\\Providers\\ViewServiceProvider',
        ],
];