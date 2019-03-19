<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                Team 
                <img src = "/assets/img/TL.jpg" class = "tw-w-10">
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
                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li>
                            <router-link to="/subscribe" class="nav-link">Subscribe</router-link>
                        </li>
                    </template>

                    <template v-if = "isCallCenterOperator">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                Crisis Information
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/crisis/new" class="nav-link">New Crisis</router-link>
                            </div>
                        </li>
                    </template>

                    <template v-if = "isCrisisManager">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                Crisis Information
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/crisis/manage" class="nav-link">Manage Crisis</router-link>
                                <router-link to="/crisis/archive" class="nav-link">View Archived Crisis</router-link>
                            </div>
                        </li>
                    </template>

                    <template v-if = "isCivilDefencesAdmin">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                isCivilDefencesAdmin
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            </div>
                        </li>
                    </template>

                    <template v-if = "isAccountManager">
                          <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                Account
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/account/register" class="nav-link">Register Accounts</router-link>
                                <router-link to="/account/manage" class="nav-link">Manage Accounts</router-link>
                            </div>
                        </li>
                    </template>

                    <template v-if = "currentUser">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="tw-capitalize nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ currentUser.name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>
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
                       
            isCivilDefencesAdmin(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CivilDefencesAdmin';
            },

            isAccountManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'AccountManager';
            },
        }
    }
</script>
