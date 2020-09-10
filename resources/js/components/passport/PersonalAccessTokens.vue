<template>
    <div>
        <div>
            <div class="card">
                <div class="card-header">
                    <div class="card-header-title flex justify-between">
                        <span>
                            Personal Access Tokens
                        </span>
                        <a class="action-link" tabindex="-1" @click="showCreateTokenForm">
                            Create New Token
                        </a>
                    </div>
                </div>

                <div class="card-content">
                    <!-- No Tokens Notice -->
                    <p class="mb-0" v-if="tokens.length === 0">
                        You have not created any personal access tokens.
                    </p>

                    <!-- Personal Access Tokens -->
                    <table class="table is-fullwidth" v-if="tokens.length > 0">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr v-for="token in tokens">
                            <!-- Client Name -->
                            <td style="vertical-align: middle;">
                                {{ token.name }}
                            </td>

                            <!-- Delete Button -->
                            <td style="vertical-align: middle;">
                                <a class="button is-danger is-small" @click="revoke(token)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create Token Modal -->
        <div class="modal fade" :class="{'is-active': showCreateModal}" id="modal-create-token">
            <div class="modal-background"></div>
            <button class="modal-close is-large" @click="showCreateModal = false"></button>
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">Create Token</p>
                    </div>
                    <div class="card-content">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="form.errors.length > 0">
                            <p class="mb-0 text-red-500"><strong class="text-red-500">Whoops!</strong> Something went
                                wrong!</p>
                            <br>
                            <ul class="text-red-500">
                                <li v-for="error in form.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Token Form -->
                        <form role="form" @submit.prevent="store">
                            <!-- Name -->
                            <div class="field">
                                <label class="label">Name</label>

                                <div class="control">
                                    <input id="create-token-name" type="text" class="input" name="name"
                                           v-model="form.name">
                                </div>
                            </div>

                            <!-- Scopes -->
                            <div class="field" v-if="scopes.length > 0">
                                <label class="label">Scopes</label>

                                <div class="control">
                                    <div v-for="scope in scopes">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"
                                                       @click="toggleScope(scope.id)"
                                                       :checked="scopeIsAssigned(scope.id)">

                                                {{ scope.id }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="field">
                                <button type="button" class="button is-primary is-small" @click="store">
                                    Create
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Access Token Modal -->
        <div class="modal" :class="{'is-active': showAccessModal}" id="modal-access-token" tabindex="-1" role="dialog">
            <div class="modal-background"></div>
            <button class="modal-close is-large" @click="showAccessModal = false"></button>
            <div class="modal-content">
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">Personal Access Token</p>
                    </div>
                    <div class="card-content">
                        <p>
                            Here is your new personal access token. This is the only time it will be shown so don't lose
                            it!
                            You may now use this token to make API requests.
                        </p>

                        <div class="field">
                            <textarea class="textarea" rows="10">{{ accessToken }}</textarea>
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
            accessToken: null,
            showCreateModal: false,
            showAccessModal: false,

            tokens: [],
            scopes: [],

            form: {
                name: '',
                scopes: [],
                errors: []
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
            this.getTokens();
            this.getScopes();

            this.showCreateModal = false
        },

        /**
         * Get all of the personal access tokens for the user.
         */
        getTokens() {
            axios.get('/oauth/personal-access-tokens')
                .then(response => {
                    this.tokens = response.data;
                });
        },

        /**
         * Get all of the available scopes.
         */
        getScopes() {
            axios.get('/oauth/scopes')
                .then(response => {
                    this.scopes = response.data;
                });
        },

        /**
         * Show the form for creating new tokens.
         */
        showCreateTokenForm() {
            this.showCreateModal = true
        },

        /**
         * Create a new personal access token.
         */
        store() {
            this.accessToken = null;

            this.form.errors = [];

            axios.post('/oauth/personal-access-tokens', this.form)
                .then(response => {
                    this.form.name = '';
                    this.form.scopes = [];
                    this.form.errors = [];

                    this.tokens.push(response.data.token);

                    this.showAccessToken(response.data.accessToken);
                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.form.errors = ['Something went wrong. Please try again.'];
                    }
                });
        },

        /**
         * Toggle the given scope in the list of assigned scopes.
         */
        toggleScope(scope) {
            if (this.scopeIsAssigned(scope)) {
                this.form.scopes = _.reject(this.form.scopes, s => s == scope);
            } else {
                this.form.scopes.push(scope);
            }
        },

        /**
         * Determine if the given scope has been assigned to the token.
         */
        scopeIsAssigned(scope) {
            return _.indexOf(this.form.scopes, scope) >= 0;
        },

        /**
         * Show the given access token to the user.
         */
        showAccessToken(accessToken) {
            this.showCreateModal = false
            this.accessToken = accessToken;
            this.showAccessModal = true
        },

        /**
         * Revoke the given token.
         */
        revoke(token) {
            axios.delete('/oauth/personal-access-tokens/' + token.id)
                .then(response => {
                    this.getTokens();
                });
        }
    }
}
</script>
