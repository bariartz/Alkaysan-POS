import React from 'react';
import { Link, Head } from '@inertiajs/inertia-react';

class Callback extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      isLogin: false,
      loginMessage: '',
    }
  }

  RedirectToDashboard() {
    this.setState({isLogin: true, loginMessage: this.props.message});
    window.opener.location.reload();
    window.close();
  }

  componentDidMount() {
    this.RedirectToDashboard();
  }

  render() {
    return (
      <>
        <Head title="Redirect to Dashboard" />
      </>
    );
  }
}

export default Callback;