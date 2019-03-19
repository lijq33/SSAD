

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
        path : '/subscribe',
        component: require('./views/Subscriber.vue')
    },  
    {
        path : '/test',
        component: require('./views/test.vue')
    },
    
    //account manager
    {
        path : '/account/register',
        component: require('./views/RegisterAccount.vue'),
        meta: {
            requiresAuthAccManager: true
        }
    },    
    {
        path : '/account/manage',
        component: require('./views/ManageAccount.vue'),
        meta: {
            requiresAuthAccManager: true
        }
    },    

    {
        path : '/crisis/new',
        component: require('./views/NewCrisis.vue'),
        meta: {
            requiresAuthCCOperator: true
        }
    },
    {
        path : '/crisis/manage',
        component: require('./views/ManageCrisis.vue'),
        meta: {
            requiresAuthCrisisManager: true
        }
    },
  
    {
        path : '/crisis/archive',
        component: require('./views/ArchiveCrisis.vue'),
        meta: {
            requiresAuthCrisisManager: true
        }
    },
    {
        path : '/basemap',
        component: require('./views/BaseMap.vue'),
        meta: {
            requiresAuth: false
        }
    } 


    // requiresAuthCCOperator 
    // requiresAuthCDAdmin 
    // requiresAuthCrisisManager 
    // requiresAuthAccManager

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
