<!-- /.navbar-top-links -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse collapse" style="height: 1px;">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="بحث...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>

                <li>
                    <a href="{{ route('home') }}"><i class="fa fa-dashboard fa-fw"></i> لوحة التحكم</a>
                </li>


            @if(Entrust::ability('admin', 'employees-permissions'))
                <li>
                    <a href="employee"><i class="fa fa fa-eye fa-fw"></i> صلاحيات الموظف<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('employee.index') }}">الكل</a></li>
                        <li><a href="{{ route('employee.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('employee.trash') }}">المحذوفات</a></li>
                        <li>
                            <a href="#"><i class="fa fa-money fa-fw"></i> السلفيات <span class="fa arrow"></span></a>
                            <ul class="nav nav-third-level collapse">
                                <li><a href="{{ route('employeeBorrow.index') }}">الكل</a></li>
                                <li><a href="{{ route('employeeBorrow.create') }}">أضف جديد</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'deposit-withdraw'))
                <li>
                    <a href="{{ route('depositwithdraw.index') }}"><i class="fa fa-briefcase fa-fw"></i> وارد /
                        منصرف</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'bank-cash'))
                <li>
                    <a href="{{ route('bank-profile.index') }}">
                        <i class="fa fa-briefcase fa-fw"></i> وارد / منصرف بنك</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'financial-custody-items'))
                <li>
                    <a href="{{ route('financialCustodyItems.index') }}"><i class="fa fa-briefcase fa-fw"></i> مصروفات
                        عهدتي</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'manage-financial-custody'))
                <li>
                    <a href="{{ route('financialCustody.index') }}"><i class="fa fa-briefcase fa-fw"></i> إدارة العهد</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'show-user-log'))
                <li>
                    <a href="{{ route('userLog.index') }}"><i class="fa fa-file-pdf-o fa-fw"></i>سجلات المستخدمين</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'attendance'))
                <li>
                    <a href="attendances"><i class="fa fa-table fa-fw"></i> حضور/انصراف الموظف<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('attendance.index') }}">الكل</a></li>
                        <li><a href="{{ route('attendance.checkin') }}">تسجيل حضور</a></li>
                        <li><a href="{{ route('attendance.checkout') }}">تسجيل انصراف</a></li>
                        <li><a href="{{ route('attendance.manualadding') }}">تسجيل جديد</a></li>
                        <li><a href="{{ route('financialCustody.index', 'all') }}">سجل العهد</a></li>
                        <li><a href="{{ route('salary.index') }}">مرتبات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'manage-bank-profile'))
                <li>
                    <a href="client-processes"><i class="fa fa-edit fa-fw"></i>إدارة البنوك<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('bank-profile.index') }}">الكل</a></li>
                        <li><a href="{{ route('bank-profile.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('bank-profile.trash') }}">المحذوفات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'new-process-client'))
                <li>
                    <a href="client-processes"><i class="fa fa-edit fa-fw"></i> عملية جديدة عميل<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('client.process.index') }}">الكل</a></li>
                        <li><a href="{{ route('client.process.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('client.process.trash') }}">المحذوفات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'new-process-supplier'))
                <li>
                    <a href="supplier-processes"><i class="fa fa-share-square-o  fa-fw"></i> عملية جديدة مورد<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('supplier.process.index') }}">الكل</a></li>
                        <li><a href="{{ route('supplier.process.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('supplier.process.trash') }}">المحذوفات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'new-client'))
                <li>
                    <a href="clients"><i class="fa fa-user fa-fw"></i> عميل جديد<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('client.index') }}">الكل</a></li>
                        <li><a href="{{ route('client.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('client.trash') }}">المحذوفات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'new-supplier'))
                <li>
                    <a href="suppliers"><i class="fa fa-share fa-fw"></i> مورد جديد<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('supplier.index') }}">الكل</a></li>
                        <li><a href="{{ route('supplier.create') }}">أضف جديد</a></li>
                        <li><a href="{{ route('supplier.trash') }}">المحذوفات</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'new-outgoing'))
                <li>
                    <a href="expenses"><i class="fa fa-files-o fa-fw"></i> مصروف جديد<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('expenses.index') }}">الكل</a></li>
                        <li><a href="{{ route('expenses.create') }}">أضف جديد</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', 'query-invoice'))
                <li>
                    <a href="invoices"><i class="fa fa-share-square-o  fa-fw"></i> إصدار فاتورة<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="{{ route('invoice.index') }}">الكل</a></li>
                        <li><a href="{{ route('invoice.create') }}">أضف جديد</a></li>
                    </ul>
                </li>
            @endif

            @if(Entrust::ability('admin', ''))
                <li>
                    <a href="#"><i class="fa fa-bank fa-fw"></i> بنك جديد</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-briefcase fa-fw"></i> وارد منصرف بنك</a>
                </li>
                <li>
                    <a href=#"><i class="fa fa-list-alt fa-fw"></i> دراسة جدوى العملية</a>
                </li>
            @endif

            @if(Entrust::ability('admin', 'view-reports'))
                <li style="font-size: 20px;">
                    <a href="reports"><i class="fa fa-bar-chart-o"></i> التقارير<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        @if(Entrust::ability('admin', 'query-client'))
                            <li><a href="{{ route('reports.client.accountStatement.index') }}">عميل</a></li>
                            <li><a href="{{ route('reports.client.analyticalCenter.index') }}">المركز التحليلى
                                    للعملاء</a>
                            </li>
                        @endif
                        @if(Entrust::ability('admin', 'query-supplier'))
                            <li><a href="{{ route('reports.supplier.accountStatement.index') }}">مورد</a></li>
                            <li><a href="{{ route('reports.supplier.analyticalCenter.index') }}">المركز التحليلى
                                    للموردين</a></li>
                        @endif

                        @if(Entrust::ability('admin', 'query-cost-center'))
                            <li><a href="{{ route('reports.process.cost-center.index') }}">مركز التكلفة</a></li>
                        @endif
                        <li><a href="{{ route('reports.expenses.index') }}">استعلام عن مصروف</a></li>
                        <li><a href="{{ route('reports.employees.totalSalaries.index') }}">مرتبات الشهر</a></li>
                        <li><a href="{{ route('reports.employees.borrow.long.index') }}">سلفيات الموظفين المستديمة</a>
                        </li>
                        <li><a href="{{ route('reports.employees.borrow.small.index') }}">سلفيات الموظفين الصغرى</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>