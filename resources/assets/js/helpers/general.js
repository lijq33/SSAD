export function initialize(store, router) {
    router.beforeEach((to, from, next) => {
        // const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
        const requiresAuthCCOperator = to.matched.some(record => record.meta.requiresAuthCCOperator);
        const requiresAuthCDAdmin = to.matched.some(record => record.meta.requiresAuthCDAdmin);
        const requiresAuthCrisisManager = to.matched.some(record => record.meta.requiresAuthCrisisManager);
        const requiresAuthAccManager = to.matched.some(record => record.meta.requiresAuthAccManager);

        const currentUser = store.state.currentUser;
    
        const requiresAuth = requiresAuthCCOperator || requiresAuthCDAdmin || requiresAuthCrisisManager || requiresAuthAccManager;

        
        if(requiresAuth && !currentUser) {
            next('/login');
        } else if( (to.path == '/login') && currentUser) {
            next('/');
        } else {
            if(requiresAuthCCOperator && currentUser.roles !== 'CallCenterOperator'){
                next('/');
            }

            if(requiresAuthCDAdmin && currentUser.roles !== 'CrisisManager'){
                next('/');
            }

            if(requiresAuthCrisisManager  && currentUser.roles !== 'CivilDefencesAdmin'){
                next('/');
            }

            if(requiresAuthAccManager && currentUser.roles !== 'AccountManager'){
                next('/');
            }

            next();
        }


      

       

    });
    
    axios.interceptors.response.use(null, (error) => {
        if (error.response.status == 401) {
            store.commit('logout');
            router.push('/login');
        }

        return Promise.reject(error);
    });

    if (store.getters.currentUser) {
        setAuthorization(store.getters.currentUser.token);
    }
}

export function setAuthorization(token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`
}