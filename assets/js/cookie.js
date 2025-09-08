// ======================= LÓGICA DO BANNER DE COOKIES =======================

document.addEventListener('DOMContentLoaded', () => {

    const cookieConsentBanner = document.getElementById('cookie-consent-banner');
    const acceptCookieButton = document.getElementById('accept-cookie-consent');
    const COOKIE_NAME = 'user_cookie_consent';
    const COOKIE_EXPIRATION_DAYS = 365; 

    // Função para definir o cookie
    const setCookie = (name, value, days) => {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/; SameSite=Lax";
    };

    // Função para obter o cookie
    const getCookie = (name) => {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    };

    // Função para esconder o banner
    const hideBanner = () => {
        cookieConsentBanner.classList.add('hidden');
    };

    if (getCookie(COOKIE_NAME)) {
        hideBanner();
    } else {
        cookieConsentBanner.classList.remove('hidden');
    }

    acceptCookieButton.addEventListener('click', () => {
        setCookie(COOKIE_NAME, 'true', COOKIE_EXPIRATION_DAYS);
        hideBanner();
    });

});