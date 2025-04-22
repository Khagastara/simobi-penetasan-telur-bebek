body.dashboard-layout {
    background-color: #f8f9fa;
    overflow-x: hidden;
}

.wrapper {
    display: flex;
    width: 100%;
    align-items: stretch;
}

#sidebar {
    min-width: 250px;
    max-width: 250px;
    min-height: 100vh;
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -250px;
}

#sidebar .sidebar-header {
    background-color: #343a40;
}

#sidebar ul.components {
    padding: 20px 0;
}

#sidebar ul li a {
    padding: 10px 15px;
    display: block;
    text-decoration: none;
    transition: all 0.3s;
}

#sidebar ul li a:hover {
    background-color: #495057;
}

#sidebar ul li.active > a {
    background-color: #007bff;
}

#content {
    width: 100%;
    min-height: 100vh;
    transition: all 0.3s;
}

#content.active {
    width: calc(100% + 250px);
}

@media (max-width: 768px) {
    #sidebar {
        margin-left: -250px;
    }
    #sidebar.active {
        margin-left: 0;
    }
    #content {
        width: 100%;
    }
    #content.active {
        width: calc(100% - 250px);
    }
}
