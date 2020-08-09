<ul class="nav nav--no-borders flex-column">
    <li class="nav-item">
        <a class="nav-link " href="transaction-history.html">
            <i class="material-icons">dashboard</i>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="nav-item dropdown
    {{( request()->is('admin/teacher')
    or
     request()->is('admin/student'
     )) ? 'active show' : ''
    }}
    ">
        <a class="nav-link dropdown-toggle " data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="true">
            <i class="material-icons">person</i>
            <span>Manage User</span>
        </a>
        <div class="dropdown-menu dropdown-menu-small {{(request()->is('admin/teacher')) ? 'show' : ''}}">
            <a class="dropdown-item {{(request()->is('admin/teacher')) ? 'active' : ''}}" href="{{route('indexTeacher')}}">Teachers</a>
            <a class="dropdown-item " href="file-manager-cards.html">Students</a>
        </div>
    </li>

</ul>
