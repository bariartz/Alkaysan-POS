import React, { createContext } from 'react';
import SwitchBranch from './PilihCabang';
import Login from './Auth/Login';
import Main from './Admin/Main';
import { Route, Routes } from "react-router-dom";

class Welcome extends React.Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <>
                { this.props.auth.user ? (
                    <Routes>
                        <Route exact path='/' element={<SwitchBranch {...this.props} />} />
                        <Route path='dashboard' element={<Main {...this.props} />} />
                    </Routes>
                ) : (
                    <Login {...this.props} />
                )}
            </>
        );
    }
}

export default Welcome;
