import React from 'react';
import { render } from 'react-dom';
import { createInertiaApp } from '@inertiajs/inertia-react';
import { InertiaProgress } from '@inertiajs/progress';
import { GlobalStateProvider } from './Components/AppContext';

const appName = window.document.getElementsByTagName('title')[0]?.innerText;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}`),
    setup({ el, App, props }) {
        return render(
            <GlobalStateProvider props={{...props, appName: appName}}>
                <App {...props} />
            </GlobalStateProvider>,
            el
        );
    },
});

InertiaProgress.init({ color: '#ed3237' });
