<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            
            <router-link class="navbar-brand" to="/">
                <img src = "/assets/img/TL.jpg" class = "tw-w-12">
            </router-link>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">

                    <!-- EVERYONE CAN SEE -->
                    <li>
                        <router-link to="/map" class="nav-link">Map</router-link>
                    </li>
                    
                    <!-- Only when not logged in -->
                    <template v-if = "!currentUser">

                        <!-- For public to submit crisis themselves -->
                        <li>
                            <router-link to="/report/crisis" class="nav-link">Report Crisis</router-link>
                        </li>

                        <!-- Login -->
                         <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>

                        <!-- Subscribe to crisis alerts -->
                        <li>
                            <b-nav-item-dropdown text="Subscription" right>
                                <router-link to="/subscribe" class="nav-link">Subscribe</router-link>
                                <router-link to="/unsubscribe" class="nav-link">Unsubscribe</router-link>
                            </b-nav-item-dropdown>
                        </li>
                    </template>

                    <template v-if = "isCallCenterOperator">
                        <li>
                            <router-link to="/crisis/new" class="nav-link">New Crisis</router-link>
                        </li>
                    </template>

                    <template v-if = "isCrisisManager">
                        <li>
                            <b-nav-item-dropdown text="Crisis Information" right>
                                <router-link to="/crisis/manage" class="nav-link">Manage Crisis</router-link>
                                 <router-link to="/crisis/managePublic" class="nav-link">Approve Crisis</router-link>
                                <router-link to="/crisis/archive" class="nav-link">View Archived Crisis</router-link>
                            </b-nav-item-dropdown>
                        </li>
                    </template>

                    <template v-if = "isAccountManager">
                        <li>
                            <b-nav-item-dropdown text="Account" right>
                                <router-link to="/account/register" class="nav-link">Register Accounts</router-link>
                                <router-link to="/account/manage" class="nav-link">Manage Accounts</router-link>
                            </b-nav-item-dropdown>
                        </li>
                    </template>

                    <template v-if = "currentUser">
                        <li>
                            <b-nav-item-dropdown :text="currentUser.name" right>
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </b-nav-item-dropdown>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: 'app-header',
        methods: {
            logout() {
                axios.post('/api/auth/logout');
                this.$store.commit('logout');
                this.$router.push('/login');
            }
        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
        
            isCallCenterOperator(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CallCenterOperator';
            },
                        
            isCrisisManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CrisisManager';
            },

            isAccountManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'AccountManager';
            },
        }
    }
</script>
