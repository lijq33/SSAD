

export const routes = [
    {
        path : '/',
        component: require('./views/Home.vue')
    },
    {
        path : '/pubcrisis',
        component: require('./views/PublicCrisis.vue')
    },
    {
        path : '/login',
        component: require('./views/Login.vue')
    },
    {
        path : '/subscribe',
        component: require('./views/Subscribe.vue')
    },  
    {
        path : '/unsubscribe',
        component: require('./views/Unsubscribe.vue')
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
        path : '/map',
        component: require('./views/BaseMap.vue'),
    }


    // requiresAuthCCOperator 
    // requiresAuthCDAdmin 
    // requiresAuthCrisisManager 
    // requiresAuthAccManager
];
