import { useState } from 'react';
import { Head } from '@inertiajs/inertia-react';
import Alert from '../../Components/Alert';
import { logoMode } from '../Admin/Components/Helpers';
import { Translations as t } from '../../Mixins/translations';
import { useGlobalState } from '@/Components/AppContext';

function Login() {
  const { globalState } = useGlobalState()
  const [isLoading, setIsLoading] = useState(false)
  const date = new Date(),
    thisYear = date.getFullYear()

  const loginOauth = (win, h, w) => {
    const y = win.top.outerHeight / 2 + win.top.screenY - ( h / 2)
    const x = win.top.outerWidth / 2 + win.top.screenX - ( w / 2)

    setIsLoading(true)

    const openWindowOauth = win.open('https://kasir.test/oauth/redirect', 'newWindow', `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${y}, left=${x}`);

    var timer = setInterval(() => {
      if(openWindowOauth.closed) {
        clearInterval(timer)
        setIsLoading(false)
      }
    }, 1000)
  }

  return (
    <>
      <Head title={ t('Login') } />
      <div className="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
          <div className="max-w-6xl mx-auto sm:px-6 lg:px-8 items-center text-center">
              <div style={{maxWidth: "300px"}}>
                <img className="mb-4" src={ logoMode(globalState.isDarkModeEnabled) } alt="" width="100%"/>
                <h1 className="mb-3 font-semibold text-center text-3xl text-black dark:text-white">{ t('Login') }</h1>
                { globalState.flash.message && (
                  <Alert alert_message={ globalState.flash.message } />
                )}
                <button onClick={ () => loginOauth(window, 600, 600) } disabled={ isLoading || globalState.flash.message == t('loggedIn') } className="text-white dark:text-white py-3 px-3 rounded-full w-full" type="button" style={{backgroundColor: "#ed3237", boxShadow: "rgba(100, 100, 111, 0.2) 0px 7px 29px 0px"}}>{ isLoading || globalState.flash.message == t('loggedIn') ? ( <> { t('Loading') }... </> ) : ( <> { t('continueWithAlkaysanAccount') } </>) }</button>
                <p className="mt-5 mb-3 text-muted dark:text-white">&copy; 2021 - { thisYear }</p>
              </div>
          </div>
      </div>
    </>
  )
}

export default Login
