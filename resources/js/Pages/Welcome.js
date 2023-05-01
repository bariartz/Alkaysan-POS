import React, { useEffect } from 'react';
import SwitchBranch from './PilihCabang';
import Login from './Auth/Login';
import Main from './Admin/Main';
import { useGlobalState } from '@/Components/AppContext';
import styles from '../app.module.css'

function Welcome(){
    const { globalState, setGlobalState } = useGlobalState()

    const detectSchemeMode = () => {
        window.matchMedia("(prefers-color-scheme: dark)")
        .addEventListener('change', event => {
            const isDarkMode = false
            const darkTheme = event.matches;
            if(darkTheme) {
                isDarkMode = true
            }
            setGlobalState((prevProps) => ({
                ...prevProps,
                isDarkModeEnabled: isDarkMode
            }))
        });
    }
    
    useEffect(() => {
        detectSchemeMode()
    }, [])

    return (
        <>
            <div className={ [styles['font-sans'], styles.antialiased, styles['bg-mode-light'], styles['dark:bg-mode-dark']].join(' ') }>
                {
                    globalState.auth.user ? (
                        globalState.store ? <Main /> : <SwitchBranch />
                    ) : <Login />
                }
            </div>
        </>
    )
}

export default Welcome;
