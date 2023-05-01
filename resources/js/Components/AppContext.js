import { createContext, useContext, useState } from 'react'
import { schemeMode } from '@/Pages/Admin/Components/Helpers'

export const GlobalStateContext = createContext({})

export const GlobalStateProvider = ({ children, props }) => {
  const [globalState, setGlobalState] = useState({
    appName: props.appName,
    isDarkModeEnabled: schemeMode(),
    isDashboardPage: false,
    page: null,
    menu: [],
    ...children.props.initialPage.props
  })

  return (
    <GlobalStateContext.Provider value={{  globalState, setGlobalState  }}>
      {children}
    </GlobalStateContext.Provider>
  )
}

export const useGlobalState = () => useContext(GlobalStateContext)