<?php

namespace App\Helpers;

class AssetHelper
{
    // Common assets for all modules
    protected static function getCommonAssets()
    {
        return [
            'scripts' => [
                asset('js/global.min.js'),
                'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
                'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
                'https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js',
                'https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js',
                'https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
                'https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js',
                'https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js',
                'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js',
                asset('js/bootstrap-select.min.js'),
                asset('js/jquery.peity.min.js'),
                asset('js/owl.carousel.js'),
                asset('js/custom.min.js'),
                asset('js/dlabnav-init.js'),
                '//cdn.jsdelivr.net/npm/sweetalert2@11',
                asset('js/bootstrap-datepicker.min.js'),
                asset('js/select2.full.min.js'),
                asset('js/picker.js'),
                asset('js/picker.date.js'),
                asset('js/toastr.min.js'),
                asset('js/toastr-init.js'),
                asset('js/notifications/notifications.js'),
                asset('js/custom_files/update_theme_mode.js'),
            ],
            'styles' => [
                'https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css',
                'https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css',
                asset('css/custom/yajra_pagination.css'),
                asset('css/bootstrap-select.min.css'),
                asset('css/owl.carousel.css'),
                asset('css/style.css'),
                asset('css/select2.min.css'),
                asset('css/default_date_picker.css'),
                asset('css/default.date.css'),
                asset('css/toastr.min.css'),
            ]
        ];
    }

    // Module-specific assets
    protected static function getModuleAssets($module)
    {
        $modules = [
            'layout' => [
                'scripts' => [
                    asset('js/global.min.js'),
                    'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
                    asset('js/bootstrap-select.min.js'),
                    asset('js/jquery.peity.min.js'),
                    asset('js/owl.carousel.js'),
                    asset('js/custom.min.js'),
                    asset('js/dlabnav-init.js'),
                ],
                'styles' => [
                    asset('css/bootstrap-select.min.css'),
                    asset('css/owl.carousel.css'),
                    asset('css/style.css'),
                ],
            ],
            'client' => [
                'scripts' => [
                    asset('js/custom_files/client.js'),
                    // Add other client-specific scripts here
                ],
                'styles' => [
                    // Add client-specific styles here
                ],
            ],
            'roles' => [
                'scripts' => [
                    asset('js/custom_files/roles.js'),
                    // Add other role-specific scripts here
                ],
                'styles' => [
                    // Add role-specific styles here
                ],
            ],
            'users' => [
                'scripts' => [
                    asset('js/custom_files/users.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'country' => [
                'scripts' => [
                    asset('js/custom_files/country.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'state' => [
                'scripts' => [
                    asset('js/custom_files/state.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'city' => [
                'scripts' => [
                    asset('js/custom_files/city.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'fee_description' => [
                'scripts' => [
                    asset('js/custom_files/fee_description.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'case_acts' => [
                'scripts' => [
                    asset('js/custom_files/case_acts.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'court_category' => [
                'scripts' => [
                    asset('js/custom_files/court_category.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'courts' => [
                'scripts' => [
                    asset('js/custom_files/courts.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'quotations' => [
                'scripts' => [
                    asset('js/custom_files/quotations.js'),
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            'dashboard' => [
                'scripts' => [
                    'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
                    'https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js',
                    'https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js',
                    'https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
                    'https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js',
                    'https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js',
                    'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js',
                    asset('js/custom_files/todo.js'),
                    asset('js/custom_files/dashboard_data.js')
                ],
                'styles' => [
                    // Add user-specific styles here
                ],
            ],
            // Add more modules as needed
        ];

        return $modules[$module] ?? ['scripts' => [], 'styles' => []];
    }

    // Get assets based on module
    public static function getAssets($module = null)
    {
        // Fetch common assets
        $commonAssets = self::getCommonAssets();

        if ($module) {
            // Fetch module-specific assets
            $moduleAssets = self::getModuleAssets($module);

            // Merge and remove duplicates
            $mergedScripts = array_unique(array_merge($commonAssets['scripts'], $moduleAssets['scripts']));
            $mergedStyles = array_unique(array_merge($commonAssets['styles'], $moduleAssets['styles']));

            return [
                'scripts' => array_values($mergedScripts),
                'styles' => array_values($mergedStyles),
            ];
        }

        // Return only common assets if no module is specified
        return [
            'scripts' => array_values(array_unique($commonAssets['scripts'])),
            'styles' => array_values(array_unique($commonAssets['styles'])),
        ];
    }
}
