export function getAccessToken () {
    // get access_token cookie for jwt_auth
    const cookies = document.cookie.split('; ');
    for(const cookie of cookies) {
        const c = cookie.split('=');
        if(c[0] === 'access_token') {
            return c[1];
        }
    }

    return null;
}
