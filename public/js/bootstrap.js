// Simple axios setup for API requests
const axios = window.axios || {};
axios.defaults = axios.defaults || {};
axios.defaults.headers = axios.defaults.headers || {};
axios.defaults.headers.common = axios.defaults.headers.common || {};
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios = axios;
