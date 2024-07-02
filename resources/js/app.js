import '../css/app.css';

console.log('App loaded');

if (import.meta.hot) {
    import.meta.hot.on('vite:beforeUpdate', () => {
        console.log('vite:beforeUpdate');
    });

    import.meta.hot.accept(() => {
        console.log('hot.accept');
    });
}