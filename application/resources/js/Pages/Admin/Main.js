require('../../bootstrap');

import React from 'react';
import { Head } from '@inertiajs/inertia-react';
import Sidebar from './Components/Partials';
import { Router, Route, Routes, useParams } from 'react-router-dom';
import axios from 'axios';
import ContentLoader from "react-content-loader";
import SwitchBranch from '../PilihCabang';

class Main extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoading: true,
    }
  }

  loadPage() {
    console.log(this.props);
    if(this.props.store || this.props.store !== '') {
      this.setState({ isLoading: false });
    }
  }

  async componentDidMount(){
    this.loadPage();
  }

  render() {
    return (
      <>
        { this.props.store == null || this.props.store == '' ? (
          <SwitchBranch {...this.props} />
        ) : (
          <>
            <Head title="Dashboard" />
            { this.state.isLoading ? (
              <>Loading...</>
            ) : (
              <>
                <div id="wrapper" className="h-screen">
                  <Sidebar {...this.props} />
                  
                  <div id="content-wrapper" className="d-flex flex-column">

                    <footer className="sticky-footer">
                      <div className="container my-auto">
                          <div className="copyright text-center my-auto">
                            <span></span>
                          </div>
                      </div>
                    </footer>
                  </div>
                </div>
                
                <a className="scroll-to-top rounded" href="#page-top">
                    <i className="fas fa-angle-up"></i>
                </a>
              </>
            )}
          </>
        )}
      </>
    );
  }
}

export default Main;