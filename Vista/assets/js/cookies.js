/**
 * Gestión de cookies
 * Almacena: biblioteca personal, preferencias de usuario, historial de visitas
 */

const LectumCookies = (() => {


    function set(name, value, days = 365) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie =
            `${encodeURIComponent(name)}=${encodeURIComponent(JSON.stringify(value))};` +
            `expires=${expires};path=/;SameSite=Lax`;
    }

    function get(name) {
        const key = encodeURIComponent(name);
        const match = document.cookie.match(new RegExp('(?:^|; )' + key + '=([^;]*)'));
        if (!match) return null;
        try {
            return JSON.parse(decodeURIComponent(match[1]));
        } catch {
            return null;
        }
    }

    function remove(name) {
        document.cookie =
            `${encodeURIComponent(name)}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/`;
    }

    function exists(name) {
        return get(name) !== null;
    }

    // Biblioteca personal
    const LIBRARY_KEY = 'lectum_library';
    const REVIEWS_KEY = 'lectum_reviews';
    const PREFS_KEY = 'lectum_prefs';
    const HISTORY_KEY = 'lectum_history';
    const CONSENT_KEY = 'lectum_consent';
    const USER_KEY = 'lectum_user';

    // Devuelve { bookId: { status, addedAt, rating?, review? } }
    function getLibrary() {
        return get(LIBRARY_KEY) || {};
    }

    function setBookStatus(bookId, status) {
        const lib = getLibrary();
        lib[bookId] = {
            ...(lib[bookId] || {}),
            status,
            addedAt: lib[bookId]?.addedAt || new Date().toISOString(),
            updatedAt: new Date().toISOString(),
        };
        set(LIBRARY_KEY, lib);
    }

    function removeBook(bookId) {
        const lib = getLibrary();
        delete lib[bookId];
        set(LIBRARY_KEY, lib);
    }

    function getBookEntry(bookId) {
        return getLibrary()[bookId] || null;
    }

    function countByStatus(status) {
        const lib = getLibrary();
        return Object.values(lib).filter(e => e.status == status).length;
    }

    // Reseñas
    function getReviews() {
        return get(REVIEWS_KEY) || [];
    }

    function addReview(bookId, rating, text) {
        const reviews = getReviews();
        // Una reseña por libro: actualiza si ya existe
        const idx = reviews.findIndex(r => r.bookId == bookId);
        const entry = {
            bookId,
            rating,
            text,
            date: new Date().toLocaleDateString('es-ES'),
            timestamp: Date.now(),
        };
        if (idx >= 0) reviews[idx] = entry;
        else reviews.unshift(entry);
        set(REVIEWS_KEY, reviews);
        return entry;
    }

    function getReviewForBook(bookId) {
        return getReviews().find(r => r.bookId == bookId) || null;
    }

    function deleteReview(bookId) {
        const reviews = getReviews().filter(r => r.bookId !== bookId);
        set(REVIEWS_KEY, reviews);
    }

    //  Preferencias de usuario
    function getPrefs() {
        return get(PREFS_KEY) || {theme: 'light', fontSize: 'md', lastGenre: ''};
    }

    function setPref(key, value) {
        const prefs = getPrefs();
        prefs[key] = value;
        set(PREFS_KEY, prefs, 730); // 2 años
    }

    // Historial de visitas
    function addToHistory(bookId) {
        let history = get(HISTORY_KEY) || [];
        history = [bookId, ...history.filter(id => id !== bookId)].slice(0, 20);
        set(HISTORY_KEY, history, 30); // 30 días
    }

    function getHistory() {
        return get(HISTORY_KEY) || [];
    }

    // Sesión de usuario
    function getUser() {
        return get(USER_KEY);
    }

    function setUser(userData) {
        set(USER_KEY, userData, 30);
    }

    function clearUser() {
        remove(USER_KEY);
    }

    // Consentimiento de cookies
    function acceptCookies() {
        LectumCookies.grantConsent();
        document.getElementById("cookieBanner").style.display = "none";
    }

    function declineCookies() {
        LectumCookies.revokeConsent();
        document.getElementById("cookieBanner").style.display = "none";
    }


    function hasConsent() {
        return get(CONSENT_KEY) == true;
    }

    function grantConsent() {
        set(CONSENT_KEY, true, 365);
    }

    function revokeConsent() {
        // Borra todo salvo el propio consentimiento
        [LIBRARY_KEY, REVIEWS_KEY, PREFS_KEY, HISTORY_KEY, USER_KEY].forEach(remove);
        set(CONSENT_KEY, false, 365);
    }

    // Exportar / importar
    function exportData() {
        return JSON.stringify({
            library: getLibrary(),
            reviews: getReviews(),
            prefs: getPrefs(),
            history: getHistory(),
            exported: new Date().toISOString(),
        }, null, 2);
    }

    function importData(jsonString) {
        try {
            const data = JSON.parse(jsonString);
            if (data.library) set(LIBRARY_KEY, data.library);
            if (data.reviews) set(REVIEWS_KEY, data.reviews);
            if (data.prefs) set(PREFS_KEY, data.prefs);
            if (data.history) set(HISTORY_KEY, data.history);
            return true;
        } catch {
            return false;
        }
    }

    //  API pública
    return {

        set, get, remove, exists,
        // Biblioteca
        getLibrary, setBookStatus, removeBook, getBookEntry, countByStatus,
        // Reseñas
        getReviews, addReview, getReviewForBook, deleteReview,
        // Preferencias
        getPrefs, setPref,
        // Historial
        addToHistory, getHistory,
        // Usuario
        getUser, setUser, clearUser,
        // Consentimiento
        hasConsent, grantConsent, revokeConsent,
        // Import / export
        exportData, importData,
    };

})();

// Disponible globalmente
window.LectumCookies = LectumCookies;