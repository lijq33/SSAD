<template>
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <router-link class="navbar-brand" to="/">
                <img src = "/assets/img/TL.jpg" class = "tw-w-10">
                Team 10
            </router-link>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    
                    <!-- EVERYONE CAN SEE -->
                   

                    <!-- Only when not logged in -->
                    <template v-if = "!currentUser">
                        <li>
                            <router-link to="/login" class="nav-link">Login</router-link>
                        </li>
                        <li>
                            <router-link to="/Subscribe" class="nav-link">Subscribe</router-link>
                        </li>
                    </template>

                    <template v-else>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                Crisis Information
                                <!--<span class="caret"></span> -->
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <router-link to="/crisis/new" class="nav-link">New Crisis</router-link>
                                <router-link to="/crisis/manage" class="nav-link">Manage Crisis</router-link>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="tw-capitalize nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                                {{ currentUser.name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a href="#!" @click.prevent="logout" class="dropdown-item">Logout</a>
                            </div>
                        </li>
                    </template>

                    <!-- Only for non-admin user-->
                    <template v-if = "currentUser">
                        <li>
                            <router-link to="/help" class="nav-link">Help</router-link>
                        </li>
                    </template>

                    <!-- For admin -->
                    <template v-if = "isSuperAdmin">
                        <li>
                            <router-link to="/register" class="nav-link">Register</router-link>
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
            isSuperAdmin() {
                return this.$store.getters.isSuperAdmin
            }
        }
    }
</script>
