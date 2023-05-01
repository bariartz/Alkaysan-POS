import logoLight from "../../../Image/logo_light.png"
import logoDark from "../../../Image/logo_dark.png"
import axios from 'axios'

const UCFirst = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

const replaceURI = (path) => {
  window.history.replaceState(null, '', path)
}

const currentURI = () => {
  return window.location.href
}

const schemeMode =  () => {
  const darkTheme = window.matchMedia("(prefers-color-scheme: dark)")
  return darkTheme.matches
}

const logoMode = (isDarkMode) => {
  return isDarkMode ? logoDark : logoLight
}

const loadPage = async (url) => {
  try {
    const response = await axios.get(url), data = await response.data
    return data
  } catch (error) {
    return error
  }
}

export { UCFirst, replaceURI, currentURI, schemeMode, logoMode, loadPage }