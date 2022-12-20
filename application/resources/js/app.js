require('./bootstrap');

import React from 'react';
import { render } from 'react-dom';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import { BrowserRouter } from "react-router-dom";
import { Provider } from 'react-redux';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Alkaysan Kasir App';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        const store = React.createContext({ store: '' });
        return render(
            <Provider state={store}>
                <BrowserRouter>
                    <App {...props} />
                </BrowserRouter>
            </Provider>,
            el
        );
    },
});

InertiaProgress.init({ color: '#4B5563' });
