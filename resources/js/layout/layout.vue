<template>
    <q-layout view="hHh LpR fFf">
        <q-header elevated class="bg-grey-14 text-white">
            <q-toolbar>
                <q-btn v-if="$page.props.auth.user" dense flat round icon="menu" @click="toggleLeftDrawer" />

                <q-toolbar-title>
                    <q-avatar>
                        <img
                            src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg"
                        />
                    </q-avatar>
                    TITLE
                </q-toolbar-title>
                <q-space />
                <q-tabs inline-label class="text-white">
                    <Link href="/" as="q-tab">
                        <q-tab name="home" label="Home" icon="home" />
                    </Link>
                    <Link href="/profile" as="q-tab">
                        <q-tab
                            name="profile"
                            label="Profile"
                            icon="account_circle"
                        />
                    </Link>
                    <q-tab
                        v-if="$page.props.auth.user"
                        name="logout"
                        label="logout"
                        @click="logout"
                        icon="logout"
                    />
                    <q-tab
                        v-else
                        name="login"
                        label="Login"
                        @click="dialogLogin = true"
                        icon="login"
                    />
                    <!-- <q-btn-dropdown auto-close stretch flat icon="more" label="More...">
                        <q-list>
                            <q-item clickable @click="tab = 'movies'">
                                <q-item-section>Movies</q-item-section>
                            </q-item>

                            <q-item clickable @click="tab = 'photos'">
                                <q-item-section>Photos</q-item-section>
                            </q-item>
                        </q-list>
                    </q-btn-dropdown> -->
                </q-tabs>
            </q-toolbar>
        </q-header>

        <q-drawer v-if="$page.props.auth.user" show-if-above v-model="leftDrawerOpen" side="left" elevated>
            <q-list class="q-mt-md">
                <Link href="/dashboard" as="q-item">
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="home" />
                        </q-item-section>
                        <q-item-section>Dashboard</q-item-section>
                    </q-item>
                </Link>
                <Link href="/user" as="q-item">
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="person" />
                        </q-item-section>
                        <q-item-section>User</q-item-section>
                    </q-item>
                </Link>
                <Link href="/berita" as="q-item">
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="newspaper" />
                        </q-item-section>
                        <q-item-section>berita</q-item-section>
                    </q-item>
                </Link>
            </q-list>
        </q-drawer>

        <q-page-container>
            <slot />
        </q-page-container>
        <!-- DIALOG -->
        <!-- persistent = jika di klick, maka ndk keluar -->
        <q-dialog persistent v-model="dialogLogin">
            <q-card style="width: 700px">
                <q-toolbar>
                    <q-toolbar-title v-if="!reg">LOGIN</q-toolbar-title>
                    <q-toolbar-title v-if="reg">Register</q-toolbar-title>
                    <!-- unelevated = biar tidak ada shadow nya-->
                    <!-- dense = kasih kecil sedikit -->
                    <q-btn
                        round
                        icon="cancel"
                        color="teal-4"
                        text-color="color"
                        unelevated
                        dense
                        @click="dialogLogin = false"
                    ></q-btn>
                </q-toolbar>
                <span v-if="status.length" class="text-cyan q-ml-lg">{{
                    status
                }}</span>
                <q-card-section>
                    <q-form v-if="!reg" class="q-gutter-sm">
                        <!-- outlined = border nya -->
                        <q-input
                            outlined
                            label="username"
                            v-model="form.username"
                        >
                            <!-- template v-slot prepend = tampil sebelum tulisan -->
                            <template v-slot:prepend>
                                <q-icon name="person" />
                            </template>
                        </q-input>
                        <q-input
                            outlined
                            label="Password"
                            type="password"
                            v-model="form.password"
                        >
                            <template v-slot:prepend>
                                <q-icon name="lock" />
                            </template>
                        </q-input>
                        <div>
                            <q-btn
                                @click="login"
                                label="login"
                                style="width: 100%"
                                icon="login"
                                color="cyan"
                            />
                        </div>
                        <div>
                            dont have account?
                            <span @click="ubah">Sign Up</span>
                        </div>
                    </q-form>
                    <!-- register -->
                    <q-form v-if="reg" class="q-gutter-sm">
                        <q-input
                            :hint="errors.name"
                            outlined
                            label="name"
                            v-model="register.name"
                        >
                            <template v-slot:prepend>
                                <q-icon name="person" />
                            </template>
                        </q-input>
                        <q-input
                            :hint="errors.username"
                            outlined
                            label="username"
                            v-model="register.username"
                        >
                            <template v-slot:prepend>
                                <q-icon name="person" />
                            </template>
                        </q-input>
                        <q-input
                            :hint="errors.password"
                            type="password"
                            outlined
                            label="password"
                            v-model="register.password"
                        >
                            <template v-slot:prepend>
                                <q-icon name="lock" />
                            </template>
                        </q-input>
                        <q-input
                            :hint="errors.email"
                            outlined
                            label="email"
                            v-model="register.email"
                        >
                            <template v-slot:prepend>
                                <q-icon name="email" />
                            </template>
                        </q-input>
                        <div class="q-gutter-sm text-right">
                            <q-btn
                                label="register"
                                @click="registerSave"
                                color="teal"
                            />
                            <q-btn
                                label="login"
                                @click="reg = false"
                                color="cyan"
                                class="q-mr-sm"
                            />
                        </div>
                    </q-form>
                </q-card-section>
            </q-card>
        </q-dialog>
    </q-layout>
</template>

<script>
import { ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";

export default {
    props: {
        errors: Object,
    },

    setup() {
        // ref hanya di deklarasi biasa
        // reactive deklarasi jika data object
        const leftDrawerOpen = ref(false);
        const dialogLogin = ref(false);
        const status = ref("");
        const form = reactive({
            username: "",
            password: "",
        });
        const register = reactive({
            name: "",
            username: "",
            password: "",
            email: "",
        });

        const reg = ref(false);

        function ubah() {
            reg.value = true;
        }

        function registerSave() {
            router.visit("/register", {
                method: "post",
                data: {
                    name: register.name,
                    username: register.username,
                    password: register.password,
                    email: register.email,
                },
                preserveScroll: true,
                // biar tidak ter refresh
                preserveState: true,
                onSuccess: () => {
                    register.name = "";
                    register.username = "";
                    register.password = "";
                    register.email = "";
                    status.value = "REGISTER BERHASIL";
                },
            });
        }

        function login() {
            router.post("/login", form);
            dialogLogin.value = false;
        }

        function logout() {
            router.get("/logout");
        }

        return {
            logout,
            login,
            status,
            registerSave,
            register,
            ubah,
            reg,
            form,
            dialogLogin,
            leftDrawerOpen,
            toggleLeftDrawer() {
                leftDrawerOpen.value = !leftDrawerOpen.value;
            },
        };
    },
};
</script>
