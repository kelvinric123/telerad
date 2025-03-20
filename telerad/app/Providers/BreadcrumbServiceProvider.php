<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BreadcrumbServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $breadcrumbs = $this->generateBreadcrumbs();
            $view->with('breadcrumbs', $breadcrumbs);
        });
    }

    /**
     * Generate breadcrumbs based on current route
     */
    private function generateBreadcrumbs(): array
    {
        $breadcrumbs = [];
        $currentRouteName = Route::currentRouteName();
        
        if (!$currentRouteName) {
            return $breadcrumbs;
        }

        // Dashboard doesn't need breadcrumbs as it's the home
        if ($currentRouteName === 'dashboard') {
            return $breadcrumbs;
        }

        $routeParts = explode('.', $currentRouteName);
        $routeBase = $routeParts[0];
        $routeAction = $routeParts[1] ?? null;

        // Common route patterns
        switch ($routeBase) {
            case 'patients':
                $breadcrumbs[] = [
                    'label' => 'Patients',
                    'url' => route('patients.index')
                ];
                
                if (in_array($routeAction, ['show', 'edit']) && request()->route('id')) {
                    $patientId = request()->route('id');
                    
                    if ($routeAction === 'edit') {
                        $breadcrumbs[] = [
                            'label' => 'Patient #' . $patientId,
                            'url' => route('patients.show', $patientId)
                        ];
                        $breadcrumbs[] = [
                            'label' => 'Edit'
                        ];
                    } else {
                        $breadcrumbs[] = [
                            'label' => 'Patient #' . $patientId
                        ];
                    }
                }
                break;
                
            case 'studies':
                $breadcrumbs[] = [
                    'label' => 'Studies',
                    'url' => route('studies.index')
                ];
                
                if ($routeAction === 'show' && request()->route('studyId')) {
                    $studyId = request()->route('studyId');
                    $breadcrumbs[] = [
                        'label' => 'Study #' . $studyId
                    ];
                }
                break;
                
            case 'reports':
                $breadcrumbs[] = [
                    'label' => 'Reports'
                ];
                
                if (in_array($routeAction, ['create', 'show', 'edit'])) {
                    if ($routeAction === 'create' && request()->route('studyId')) {
                        $studyId = request()->route('studyId');
                        $breadcrumbs[0]['url'] = route('studies.index');
                        
                        $breadcrumbs[] = [
                            'label' => 'Study #' . $studyId,
                            'url' => route('studies.show', $studyId)
                        ];
                        
                        $breadcrumbs[] = [
                            'label' => 'Create Report'
                        ];
                    } elseif (in_array($routeAction, ['show', 'edit']) && request()->route('id')) {
                        $reportId = request()->route('id');
                        
                        if ($routeAction === 'edit') {
                            $breadcrumbs[] = [
                                'label' => 'Report #' . $reportId,
                                'url' => route('reports.show', $reportId)
                            ];
                            $breadcrumbs[] = [
                                'label' => 'Edit'
                            ];
                        } else {
                            $breadcrumbs[] = [
                                'label' => 'Report #' . $reportId
                            ];
                        }
                    }
                }
                break;
                
            case 'report-templates':
                $breadcrumbs[] = [
                    'label' => 'Report Templates',
                    'url' => route('report-templates.index')
                ];
                
                if (in_array($routeAction, ['create', 'edit'])) {
                    $breadcrumbs[] = [
                        'label' => $routeAction === 'create' ? 'Create Template' : 'Edit Template'
                    ];
                }
                break;
                
            case 'profile':
                $breadcrumbs[] = [
                    'label' => 'Profile'
                ];
                break;
        }
        
        return $breadcrumbs;
    }
} 