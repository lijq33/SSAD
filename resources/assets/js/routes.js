

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
        component: require('./views/Register.vue')
    },    
    {
        path : '/newcrisis',
        component: require('./views/NewCrisis.vue')
    },
    {
        path : '/viewcrisis',
        component: require('./views/ViewCrisis.vue')
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
