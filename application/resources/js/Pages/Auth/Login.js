import React, { useEffect } from 'react';
import { Link, Head, usePage } from '@inertiajs/inertia-react';
import Alert from '../../Components/Alert';

class Login extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      date: new Date(),
      isLoading: false,
    }
  }

  loginOauth(win, h, w) {
    const y = win.top.outerHeight / 2 + win.top.screenY - ( h / 2);
    const x = win.top.outerWidth / 2 + win.top.screenX - ( w / 2);

    this.setState({ isLoading: true });

    const openWindowOauth = win.open('https://kasir.test/oauth/redirect', 'newWindow', `toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=${w}, height=${h}, top=${y}, left=${x}`);

    var timer = setInterval(() => {
      if(openWindowOauth.closed) {
        clearInterval(timer);
        this.setState({ isLoading: false });
      }
    }, 1000);
  }

  render() {
    return (
      <>
          <Head title="Masuk" />
          <div className="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
              <div className="max-w-6xl mx-auto sm:px-6 lg:px-8 items-center text-center">
                  <div style={{maxWidth: "300px"}}>
                    <img className="mb-4" src="https://app.alkaysan.co.id/assets/img/logo_alkaysan_karyawan.png" alt="" width="100%"/>
                    <h1 className="mb-3 font-semibold text-center text-3xl text-black dark:text-white">Masuk Aplikasi Kasir</h1>
                    { this.props.flash.message && (
                      <Alert alert_message={ this.props.flash.message } />
                    )}
                    <button onClick={ () => this.loginOauth(window, 600, 600) } disabled={ this.state.isLoading || this.props.flash.message == 'logged in' } className="text-white dark:text-white py-3 px-3 rounded-full w-full" type="button" style={{backgroundColor: "#ed3237", boxShadow: "rgba(100, 100, 111, 0.2) 0px 7px 29px 0px"}}>{ this.state.isLoading || this.props.flash.message == 'logged in' ? ( <> Loading... </> ) : ( <> Lanjutkan dengan Akun Alkaysan </>) }</button>
                    <p className="mt-5 mb-3 text-muted dark:text-white">&copy; 2021 - {this.state.date.getFullYear()}</p>
                  </div>
              </div>
          </div>
      </>
    );
  }
}

export default Login;
