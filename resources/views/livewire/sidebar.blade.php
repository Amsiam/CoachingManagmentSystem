<?php

use function Livewire\Volt\{state};

state([
    'sidebarItems' => [
        [
            'title' => 'Home',
            'icon' => 'o-home',
            'link' => '/dashboard',
            'hasMore' => false,
            'permission' => null,
        ],

        [
            'title' => 'Student Admission',
            'icon' => 'o-user-plus',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Academics',
                    'icon' => 'o-home',
                    'link' => '/admission/academics',
                    'hasMore' => false,
                    'permission' => 'student.academics',
                ],
                [
                    'title' => 'Admission',
                    'icon' => 'o-home',
                    'link' => '/admission/admission',
                    'hasMore' => false,
                    'permission' => 'student.admission',
                ],
                [
                    'title' => 'Medical Admission',
                    'icon' => 'o-home',
                    'link' => '/admission/dmc',
                    'hasMore' => false,
                    'permission' => 'student.dmc',
                ],
                [
                    'title' => 'Admission Request',
                    'icon' => 'o-home',
                    'link' => '/admission/request',
                    'hasMore' => false,
                    'permission' => 'student.admissionRequest',
                ],
            ],
        ],
        [
            'title' => 'Student List',
            'icon' => 'o-user-group',
            'link' => '/student/list',
            'hasMore' => false,
            'permission' => 'student.list',
        ],
        [
            'title' => 'Expense',
            'icon' => 'o-currency-dollar',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Expense List',
                    'icon' => 'o-home',
                    'link' => '/expense/list',
                    'hasMore' => false,
                    'permission' => 'expense.list',
                ],
                [
                    'title' => 'Salary List',
                    'icon' => 'o-home',
                    'link' => '/expense/salary',
                    'hasMore' => false,
                    'permission' => 'expense.salary.list',
                ],
                [
                    'title' => 'Categories',
                    'icon' => 'o-home',
                    'link' => '/expense/categories',
                    'hasMore' => false,
                    'permission' => 'expense.category.list',
                ],
            ],
        ],
        [
            'title' => 'Book',
            'icon' => 'o-currency-dollar',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Book List',
                    'icon' => 'o-home',
                    'link' => '/book/list',
                    'hasMore' => false,
                    'permission' => 'book.list',
                ],
                [
                    'title' => 'Book Sell',
                    'icon' => 'o-home',
                    'link' => '/book/sell',
                    'hasMore' => false,
                    'permission' => 'book.sell',
                ],
            ],
        ],
        [
            'title' => 'Report',
            'icon' => 'o-presentation-chart-bar',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Admission Report',
                    'icon' => 'o-home',
                    'link' => '/report/admission',
                    'hasMore' => false,
                    'permission' => 'report.admission',
                ],
                [
                    'title' => 'Income Report',
                    'icon' => 'o-home',
                    'link' => '/report/income',
                    'hasMore' => false,
                    'permission' => 'report.income',
                ],
                [
                    'title' => 'Montly Report',
                    'icon' => 'o-home',
                    'link' => '/report/montly',
                    'hasMore' => false,
                    'permission' => 'report.monthly',
                ],
                [
                    'title' => 'Activity Log',
                    'icon' => 'o-home',
                    'link' => '/report/activity_log',
                    'hasMore' => false,
                    'permission' => 'report.activity_log',
                ],
            ],
        ],

        [
            'title' => 'Exam',
            'icon' => 'o-presentation-chart-bar',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'List',
                    'icon' => 'o-home',
                    'link' => '/exam',
                    'hasMore' => false,
                    'permission' => 'exam.list',
                ],
                [
                    'title' => 'Result',
                    'icon' => 'o-home',
                    'link' => '/result',
                    'hasMore' => false,
                    'permission' => 'exam.result',
                ],
            ],
        ],

        [
            'title' => 'Academics Esentials',
            'icon' => 'o-cog-6-tooth',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Academic Year',
                    'icon' => 'o-home',
                    'link' => '/academic_year',
                    'hasMore' => false,
                    'permission' => 'academics.academic_year',
                ],
                [
                    'title' => 'Group',
                    'icon' => 'o-home',
                    'link' => '/groups',
                    'hasMore' => false,
                    'permission' => 'academics.group',
                ],
                [
                    'title' => 'Classes',
                    'icon' => 'o-home',
                    'link' => '/classes',
                    'hasMore' => false,
                    'permission' => 'academics.class',
                ],
                [
                    'title' => 'Subjects',
                    'icon' => 'o-home',
                    'link' => '/subject',
                    'hasMore' => false,
                    'permission' => 'academics.class',
                ],
                [
                    'title' => 'Course',
                    'icon' => 'o-home',
                    'link' => '/courses',
                    'hasMore' => false,
                    'permission' => 'academics.course',
                ],
                [
                    'title' => 'Shift',
                    'icon' => 'o-home',
                    'link' => '/shifts',
                    'hasMore' => false,
                    'permission' => 'academics.course',
                ],
                [
                    'title' => 'Batch',
                    'icon' => 'o-home',
                    'link' => '/batches',
                    'hasMore' => false,
                    'permission' => 'academics.batch',
                ],
            ],
        ],
        [
            'title' => 'SMS',
            'icon' => 'o-home',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Send SMS',
                    'icon' => 'o-home',
                    'link' => '/sendsms',
                    'hasMore' => false,
                    'permission' => 'sendsms',
                ],
                [
                    'title' => 'Auto SMS',
                    'icon' => 'o-home',
                    'link' => '/auto-sms',
                    'hasMore' => false,
                    'permission' => 'sendsms',
                ],
            ],
        ],

        [
            'title' => 'User Managment',
            'icon' => 'o-user-circle',
            'hasMore' => true,
            'children' => [
                [
                    'title' => 'Admins',
                    'icon' => 'o-home',
                    'link' => '/user/admin',
                    'hasMore' => false,
                    'permission' => 'admin.list',
                ],
                [
                    'title' => 'Roles',
                    'icon' => 'o-home',
                    'link' => '/user/roles',
                    'hasMore' => false,
                    'permission' => 'role.list',
                ],
                [
                    'title' => 'Permissions',
                    'icon' => 'o-home',
                    'link' => '/user/permission',
                    'hasMore' => false,
                    'permission' => 'permission.list',
                ],
            ],
        ],
         
           [
            'title' => 'Slider',
            'icon' => 'o-home',
            'link' => '/slider',
            'hasMore' => false,
            'permission' => 'slider',
        ],
        
        
        
        [
            'title' => ' কোর্স ইনফরমেশ',
            'icon' => 'o-home',
            'link' => '/student_review',
            'hasMore' => false,
            'permission' => 'student_review',
        ],
     
    ],
]);



?>


@php

    function getPermissions($permission)
    {
        return $permission['permission'];
    }

@endphp

<div>
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-200">
        <x-menu activate-by-route>

            @foreach ($sidebarItems as $sidebarItem)
                @if ($sidebarItem['hasMore'])
                    @php
                        $allPermissions = array_map('getPermissions', $sidebarItem['children']);
                    @endphp

                    @canany($allPermissions)
                        <x-menu-sub title="{{ $sidebarItem['title'] }}" icon="{{ $sidebarItem['icon'] }}">
                            @foreach ($sidebarItem['children'] as $child)
                                @can($child['permission'])
                                    <x-menu-item title="{{ $child['title'] }}" icon="{{ $child['icon'] }}"
                                        link="{{ $child['link'] }}" />
                                @endcan
                            @endforeach
                        </x-menu-sub>
                    @endcanany
                @else
                    @can($sidebarItem['permission'])
                        <x-menu-item title="{{ $sidebarItem['title'] }}" icon="{{ $sidebarItem['icon'] }}"
                            link="{{ $sidebarItem['link'] }}" />
                    @endcan
                @endif
            @endforeach
        </x-menu>
    </x-slot:sidebar>
</div>
