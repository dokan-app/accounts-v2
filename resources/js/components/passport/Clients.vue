<style scoped>
.action-link {
    cursor: pointer;
}
</style>

<template>
    <div>
        <!--  Clients table   -->
        <div class="card">
            <div class="card-header">
                <div class="card-header-title flex justify-between">
                    <span>
                        OAuth Clients
                    </span>

                    <a class="action-link" tabindex="-1" @click="showCreateClientForm">
                        Create New Client
                    </a>
                </div>
            </div>

            <div class="card-content">
                <!-- Current Clients -->
                <p class="mb-0" v-if="clients.length === 0">
                    You have not created any OAuth clients.
                </p>

                <table class="table is-fullwidth" v-if="clients.length > 0">
                    <thead>
                    <tr>
                        <th>Client ID</th>
                        <th>Name</th>
                        <th>Secret</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="client in clients">
                        <!-- ID -->
                        <td style="vertical-align: middle;">
                            {{ client.id }}
                        </td>

                        <!-- Name -->
                        <td style="vertical-align: middle;">
                            {{ client.name }}
                        </td>

                        <!-- Secret -->
                        <td style="vertical-align: middle;">
                            <code>{{ client.secret ? client.secret : '-' }}</code>
                        </td>

                        <!-- Edit Button -->
                        <td style="vertical-align: middle;">
                            <a class="action-link" tabindex="-1" @click="edit(client)">
                                Edit
                            </a>
                        </td>

                        <!-- Delete Button -->
                        <td style="vertical-align: middle;">
                            <a class="action-link text-danger" @click="destroy(client)">
                                Delete
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!--    ==================== Create Client Model ====================  -->
        <div :class="{'is-active': isShowCreateClientForm}" class="modal">
            <div class="modal-background"></div>
            <button class="modal-close is-large" aria-label="close" @click="isShowCreateClientForm = false"></button>
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title">Create Client</div>
                    </div>
                    <div class="card-content">
                        <!-- Form Errors -->
                        <div class="notification is-danger" v-if="createForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Client Form -->
                        <div>
                            <!-- Name -->
                            <div class="field">
                                <label class="label">Name</label>

                                <div class="control">
                                    <input id="create-client-name" type="text" class="input"
                                           @keyup.enter="store" v-model="createForm.name">

                                    <span class="help">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="field">
                                <label class="label">Redirect URL</label>

                                <div class="control">
                                    <input type="text" class="input" name="redirect"
                                           @keyup.enter="store" v-model="createForm.redirect">

                                    <span class="help">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>

                            <!-- Confidential -->
                            <div class="field">
                                <label class="label">Confidential</label>

                                <div class="control">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="createForm.confidential">
                                        </label>
                                    </div>

                                    <span class="help">
                                        Require the client to authenticate with a secret. Confidential clients can hold credentials in a secure way without exposing them to unauthorized parties. Public applications, such as native desktop or JavaScript SPA applications, are unable to hold secrets securely.
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <button class="button is-primary is-small" @click="store">Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!--    ==================== Client Secret modal ====================  -->
        <div class="modal" :class="{'is-active': isShowClientSecretModel}" tabindex="-1" role="dialog">
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-title">Client Secret</div>
                    </div>
                    <div class="card-content">
                        <p>
                            Here is your new client secret. This is the only time it will be shown so don't lose it!
                            You may now use this secret to make API requests.
                        </p>
                        <div class="field">
                            <input type="text" class="input" v-model="clientSecret">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--    ==================== Edit client ====================  -->
        <div class="modal" :class="{'is-active': isShowModalEdit}">
            <div class="modal-background"></div>
            <button class="modal-close is-large" aria-label="close" @click="isShowModalEdit = false"></button>
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">Edit Client</p>
                    </div>
                    <div class="card-content">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="editForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in editForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Edit Client Form -->
                        <div role="form">
                            <!-- Name -->
                            <div class="field">
                                <label class="label">Name</label>

                                <div class="control">
                                    <input id="edit-client-name" type="text" class="input"
                                           @keyup.enter="update" v-model="editForm.name">

                                    <span class="help">
                                        Something your users will recognize and trust.
                                    </span>
                                </div>
                            </div>

                            <!-- Redirect URL -->
                            <div class="field">
                                <label class="label">Redirect URL</label>

                                <div class="control">
                                    <input type="text" class="input" name="redirect"
                                           @keyup.enter="update" v-model="editForm.redirect">

                                    <span class="help">
                                        Your application's authorization callback URL.
                                    </span>
                                </div>
                            </div>

                            <div class="field">
                                <button class="button is-primary" @click="update">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    /*
     * The component's data.
     */
    data() {
        return {
            clients: [],
            isShowCreateClientForm: false,
            isShowClientSecretModel: false,
            isShowModalEdit: false,

            clientSecret: null,

            createForm: {
                errors: [],
                name: '',
                redirect: '',
                confidential: true
            },

            editForm: {
                errors: [],
                name: '',
                redirect: ''
            }
        };
    },

    /**
     * Prepare the component (Vue 1.x).
     */
    ready() {
        this.prepareComponent();
    },

    /**
     * Prepare the component (Vue 2.x).
     */
    mounted() {
        this.prepareComponent();
    },

    methods: {
        /**
         * Prepare the component.
         */
        prepareComponent() {
            this.getClients();

            $('#modal-create-client').on('shown.bs.modal', () => {
                $('#create-client-name').focus();
            });

            $('#modal-edit-client').on('shown.bs.modal', () => {
                $('#edit-client-name').focus();
            });
        },

        /**
         * Get all of the OAuth clients for the user.
         */
        getClients() {
            axios.get('/oauth/clients')
                .then(response => {
                    this.clients = response.data;
                });
        },

        /**
         * Show the form for creating new clients.
         */
        showCreateClientForm() {
            this.isShowCreateClientForm = true
        },

        /**
         * Create a new OAuth client for the user.
         */
        store() {
            this.persistClient(
                'post',
                '/oauth/clients',
                this.createForm
            );
            this.isShowCreateClientForm = false

        },

        /**
         * Edit the given client.
         */
        edit(client) {
            this.editForm.id = client.id;
            this.editForm.name = client.name;
            this.editForm.redirect = client.redirect;

            this.isShowModalEdit = true
        },

        /**
         * Update the client being edited.
         */
        update() {
            this.persistClient(
                'put',
                '/oauth/clients/' + this.editForm.id,
                this.editForm,
            );
            this.isShowModalEdit = false
        },

        /**
         * Persist the client to storage using the given form.
         */
        persistClient(method, uri, form) {
            form.errors = [];

            axios[method](uri, form)
                .then(response => {
                    this.getClients();

                    form.name = '';
                    form.redirect = '';
                    form.errors = [];


                    if (response.data.plainSecret) {
                        this.showClientSecret(response.data.plainSecret);
                    }
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        form.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        form.errors = ['Something went wrong. Please try again.'];
                    }
                });
        },

        /**
         * Show the given client secret to the user.
         */
        showClientSecret(clientSecret) {
            this.clientSecret = clientSecret;
            console.log(clientSecret)
            this.isShowClientSecretModel = true
        },

        /**
         * Destroy the given client.
         */
        destroy(client) {
            axios.delete('/oauth/clients/' + client.id)
                .then(response => {
                    this.getClients();
                });
        }
    }
}
</script>
