export default function phpReloadPlugin() {
    return {
        name: 'php-reload-plugin',
        handleHotUpdate({ file, server }) {
            if (file.endsWith('.php')) {
                server.ws.send({
                    type: 'full-reload',
                    path: '*'
                });
            }
        }
    }
}