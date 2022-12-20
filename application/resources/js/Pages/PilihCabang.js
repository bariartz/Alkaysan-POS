import React from 'react';
import { Head } from '@inertiajs/inertia-react';
import axios from 'axios';
import ContentLoader from "react-content-loader";
import { Link, useHistory, useLocation } from "react-router-dom";
import Main from './Admin/Main';

class SwitchBranch extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      branch: [],
      isLoading: true,
      store: this.props.store,
    }
  }

  async getBranch() {
    axios.get("https://account.alkaysan.co.id/api/v1/branch?accessby=" + this.props.auth.user.id_karyawan)
    .then(response => {
      const data = response.data.data;
      this.setState({ branch: data, isLoading: false });
    })
    .catch(error => {
      console.error(error)
    })
  }

  goStore = (storeName) => {
    axios.get('/go?store=' + storeName)
    .then(response => {
      const data = response.data;
      console.log(data);
    })
    .catch(error => {
      console.log(error);
    })
  }

  async componentDidMount() {
    this.getBranch();
  }

  render() {
    return (
      <>
          <Head title="Pilih Toko" />
          <style>
            {
              `
              .list-group {
                width: 400px !important;
              }

              .list-group-item {
                margin-top: 10px;
                border-radius: none;
                background: #fff;
                cursor: pointer;
                transition: all 0.3s ease-in-out;
                box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
                position: relative;
                display: block;
                padding: 0.75rem 1.25rem;
                background-color: #fff;
                border: 1px solid rgba(0,0,0,.125);
              }

              .list-group-item:hover {
                transform: scaleX(1.1);
              }

              .check {
                opacity: 0;
                transition: all 0.6s ease-in-out;
              }

              .list-group-item:hover .check {
                opacity: 1;
              }

              .about span {
                font-size: 12px;
                margin-right: 10px;
              }

              input[type=checkbox] {
                position: relative;
                cursor: pointer;
              }

              input[type=checkbox]:before {
                content: "";
                display: block;
                position: absolute;
                width: 20px;
                height: 20px;
                top: 0px;
                left: 0;
                border: 1px solid #10a3f9;
                border-radius: 3px;
                background-color: white;

              }

              input[type=checkbox]:checked:after {
                content: "";
                display: block;
                width: 7px;
                height: 12px;
                border: solid #007bff;
                border-width: 0 2px 2px 0;
                -webkit-transform: rotate(45deg);
                -ms-transform: rotate(45deg);
                transform: rotate(45deg);
                position: absolute;
                top: 2px;
                left: 6px;
              }

              input[type="checkbox"]:checked+.check {
                opacity: 1;
              }
              `
            }
          </style>

          <div className="relative flex items-top justify-center min-h-screen sm:items-center sm:pt-0">
              <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
                  <div className="container d-flex align-items-center" style={{flexDirection: "column", flexDirection: "column", justifyContent: "center", margin: "auto",height: "90vh"}}>
                    <h1 className="mb-0 text-3xl text-center text-black dark:text-white">Pilih Cabang</h1>
                    <ul className="list-group mt-3">
                      { this.state.isLoading ? (
                        <ContentLoader 
                          speed={2}
                          width={400}
                          height={420}
                          viewBox="0 0 400 420"
                          backgroundColor="#d9d9d9"
                          foregroundColor="#ededed"
                          {...this.props}
                        >
                          <rect x="0" y="0" rx="0" ry="0" width="100%" height="50"/>
                          <rect x="0" y="60" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="120" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="180" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="240" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="300" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="360" rx="0" ry="0" width="100%" height="50" />
                          <rect x="0" y="420" rx="0" ry="0" width="100%" height="50" />
                        </ContentLoader>
                      ) : (
                        <>
                          {this.state.branch.map((item, index) => (
                            <li className="list-group-item" key={ index }>
                              <Link to='dashboard' onClick={ () => {this.goStore(item.folder.toLowerCase())} }>
                                <div className="text-center">
                                  <div className="ml-2 center">
                                    <h3 className="text-black font-semibold">{ item.nama_cabang }</h3>
                                  </div>
                                </div>
                              </Link>
                            </li>
                          ))}
                        </>
                      )}
                    </ul>
                  </div>
              </div>
          </div>
      </>
    );
  }
}
export default SwitchBranch;
