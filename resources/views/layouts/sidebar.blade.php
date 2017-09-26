<!-- /.navbar-top-links -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav ">
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
            <li>
                <a href="#"><i class="fa fa fa-eye fa-fw"></i> صلاحيات الموظف</a>
                <ul>
                    <li><a href="{{ route('employee.index') }}">الكل</a></li>
                    <li><a href="{{ route('employee.create') }}">أضف جديد</a></li>
                    <li><a href="{{ route('employee.trash') }}">المحذوفات</a></li>
                    <li>
                        <a href="#"><i class="fa fa-money fa-fw"></i> السلفيات </a>
                        <ul>
                            <li><a href="{{ route('employeeBorrow.index') }}">الكل</a> </li>
                            <li><a href="{{ route('employeeBorrow.create') }}">أضف جديد</a> </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ route('depositwithdraw.index') }}"><i class="fa fa-briefcase fa-fw"></i>وارد / منصرف</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-table fa-fw"></i> حضور/انصراف الموظف</a>
                <ul>
                    <li><a href="{{ route('attendance.index') }}">الكل</a></li>
                    <li><a href="{{ route('attendance.checkin') }}">تسجيل حضور</a></li>
                    <li><a href="{{ route('attendance.checkout') }}">تسجيل انصراف</a></li>
                    <li><a href="{{ route('attendance.manualadding') }}">تسجيل جديد</a></li>
                    <li><a href="{{ route('attendance.guardianship', 'all') }}">سجل العهد</a>
                    <li><a href="{{ route('attendance.show', 'all') }}">مرتبات</a>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-edit fa-fw"></i> عملية جديدة عميل</a>
                <ul>
                    <li><a href="{{ route('client.process.index') }}">الكل</a></li>
                    <li><a href="{{ route('client.process.create') }}">أضف جديد</a></li>
                    <li><a href="{{ route('client.process.trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-share-square-o  fa-fw"></i> عملية جديدة مورد</a>
                <ul>
                    <li><a href="{{ route('supplier.process.index') }}">الكل</a></li>
                    <li><a href="{{ route('supplier.process.create') }}">أضف جديد</a></li>
                    <li><a href="{{ route('supplier.process.trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-user fa-fw"></i> عميل جديد</a>
                <ul>
                    <li><a href="{{ route('client.index') }}">الكل</a></li>
                    <li><a href="{{ route('client.create') }}">أضف جديد</a></li>
                    <li><a href="{{ route('client.trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-share fa-fw"></i> مورد جديد</a>
                <ul>
                    <li><a href="{{ route('supplier.index') }}">الكل</a></li>
                    <li><a href="{{ route('supplier.create') }}">أضف جديد</a></li>
                    <li><a href="{{ route('supplier.trash') }}">المحذوفات</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-share-square-o  fa-fw"></i>إصدار فاتورة</a>
                <ul>
                    <li><a href="{{ route('invoice.index') }}">الكل</a></li>
                    <li><a href="{{ route('invoice.create') }}">أضف جديد</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-files-o fa-fw"></i> مصروف جديد</a>
                <ul>
                    <li><a href="{{ route('expenses.index') }}">الكل</a></li>
                    <li><a href="{{ route('expenses.create') }}">أضف جديد</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa fa-bank fa-fw"></i> بنك جديد</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-briefcase fa-fw"></i> وارد منصرف بنك</a>
            </li>
            <li>
                <a href="#"><i class="fa fa-list-alt fa-fw"></i> دراسة جدوى العملية</a>
            </li>
            <li style="font-size: 20px;">
                <a href="#"><i class="fa fa-bar-chart-o"></i>  التقارير</a>
                <ul>
                    <li><a href="{{ route('reports.client.accountStatement.index') }}">عميل</a></li>
                    <li><a href="{{ route('reports.supplier.accountStatement.index') }}">مورد</a></li>
                    <li><a href="{{ route('reports.client.analyticalCenter.index') }}">المركز التحليلى للعملاء</a></li>
                    <li><a href="{{ route('reports.supplier.analyticalCenter.index') }}">المركز التحليلى للموردين</a></li>
                    <li><a href="{{ route('reports.expenses.index') }}">استعلام عن مصروف</a></li>
                    <li><a href="{{ route('reports.employees.totalSalaries.index') }}">مرتبات الشهر</a></li>
                    <li><a href="{{ route('reports.employees.borrow.index') }}">سلفيات الموظفين</a></li>
                </ul>

            </li>
        </ul>
    </div>
</div>
