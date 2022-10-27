/**import axios */
import axios from 'axios'

/**auth api's */

const axiosInstance = axios.create()
axiosInstance.defaults.baseURL = API
axiosInstance.defaults.method = "post" /**make default method post */


// Add a response interceptor
axiosInstance.interceptors.response.use(function (response) {
    //  We don't need to Do something with response data yet!
    return response
}, function (error) {
    const auth = useAuth()

    if (error.response) {
        /**handle errors */
        switch (error.response.status) {
            case 400:
                showNotifyError400(error.response.data)
                break
            case 401:
                //disabled for now
                // showNotifyError422(error.response.data.errors)                         
                // store.dispatch("logout")
                break
            case 403:
                /**
                 * the user token is expired
                 * remove the token and show the login
                 */
                toast.info('جلسه کاری شما به اتمام رسیده است، لطفا مجددا لاگین کنید')
                auth.logout()
                break
            case 404:
                showNotifyError422(error.response.data)
                break
            case 422:
                /**we have array errors */
                showNotifyError422(error.response.data.errors)
                break
            case 500:
                showNotifyError422(error.response.data)
                break
            case 502:
                showNotifyError422(error.response.data)
                break
            default:
                showNotifyError422(error.response.data)
        }
    }
    return Promise.reject(error);
})

/**show error notification for 422 error
 * @param array|object errors
 */
function showNotifyError422(errors) {
    let err
    if (typeof errors === 'object') {
        err = errors
    }
    if (typeof errors === 'array') {
        err = errors[0]
    }
    let msg = Object.values(err)[0]

    if (errors.length === 1) {
        toast.error(errors[0])
        return
    }
    toast.error(msg[0])
}

/**show error notification for 400 error
 * @param array|object errors
 */
function showNotifyError400(errors) {
    toast.error(errors.msg)
}

export default axiosInstance