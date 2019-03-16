

export const routes = [
    {
        path : '/',
        component: require('./views/Home.vue')
    },
    {
        path : '/login',
        component: require('./views/Login.vue')
    },
    {
        path : '/register',
        component: require('./views/Register.vue'),
        meta: {
            requiresAuth: true
        }
    },    
    {
        path : '/crisis/new',
        component: require('./views/NewCrisis.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/crisis/manage',
        component: require('./views/ManageCrisis.vue'),
        meta: {
            requiresAuth: true
        }
    },
    {
        path : '/basemap',
        component: require('./views/BaseMap.vue'),
        meta: {
            requiresAuth: false
        }
    } 

    // //requires login
    // {
    //     path : '/Appointment/Create',
    //     component: require('./views/Appointment/Create'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
    // {
    //     path : '/Appointment/Manage',
    //     component: require('./views/Appointment/Manage'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
    // {
    //     path : '/Help',
    //     component: require('./views/Help'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },

    // //admin
    // {
    //     path : '/feedback/show',
    //     component: require('./views/ViewFeedback'),
    //     meta: {
    //         requiresAuth: true
    //     }
    // },
];
