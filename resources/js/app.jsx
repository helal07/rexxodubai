import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import AppLayout from './Layouts/AppLayout';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
        const tsxPages = import.meta.glob('./Pages/**/*.tsx', { eager: true });
        let page = pages[`./Pages/${name}.jsx`] || tsxPages[`./Pages/${name}.tsx`];
        if (!name.startsWith('Admin/')) {
            page.default.layout = page.default.layout || (pageContent => <AppLayout children={pageContent} />);
        }
        return page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});
