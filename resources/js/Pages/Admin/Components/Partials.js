import { useState, useEffect } from 'react';
import { UCFirst, loadPage, replaceURI } from '../Components/Helpers';
import { Translations as t } from '../../../Mixins/translations';
import { useGlobalState } from '@/Components/AppContext';
import parse from "html-react-parser";
import { Head } from '@inertiajs/inertia-react';

const SideNavBar = () => {
  const { globalState, setGlobalState } = useGlobalState()
  const [isOpen, setOpen] = useState(false)
  const onOpenMenu = () => setOpen(!isOpen)
  const [isLoading, setIsLoading] = useState(true)

  useEffect(() => {
    const fetchData = async() => {
      const sendSessionToServer = await loadPage(`?store=${ globalState.store }&response_type=json`)
      if(sendSessionToServer.success){
        setGlobalState((prevState) => ({
          ...prevState,
          menu: sendSessionToServer.app.menu
        }))
        setIsLoading(false)
      }
    }

    fetchData()
  }, []);

  const handleStoreChange = () => {
    setGlobalState((prevState) => ({
      ...prevState,
      store: null
    }))
  }

  const handleBurgerMenuClick = (name, url) => {
    setGlobalState((prevState) => ({
      ...prevState,
      page: name
    }))
    replaceURI(url)
  }

  return (
    <>
      <Head title={ t(globalState.page) } />
      <div className="w-full absolute flex overflow-hidden text-black dark:text-white bg-mode-white dark:bg-mode-dark">
        <div className="w-52 px-4 h-screen bg-mode-light dark:bg-mode-dark">
          <div className="flex items-center px-4 mt-3">
            <svg className="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
            </svg>
            <span className="ml-2 text-sm font-bold text-black dark:text-white">{ UCFirst(globalState.store ? globalState.store : globalState.appName) }</span>
          </div>
          <div className="flex flex-col items-center w-full mt-3">
            { isLoading ? (
              <>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
                <div className='flex items-center w-full h-12 px-3 rounded loading'></div>
              </>
            ) : (
              <>
                { globalState.menu.map((item, index) => (
                  <div className={`sidebar__menu__list ${ globalState.page === item.name ? 'bg-gray-300 dark:bg-gray-600' : '' }`} onClick={ () => { handleBurgerMenuClick(item.name, item.url) } } key={index}>
                    { parse(item.icon) }
                    <span className="sidebar__menu__name">{ t(item.name) }</span>
                    { item.child_menu.length > 0 ? (
                      <svg className="w-4 h-4 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor">
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                      </svg>
                    ) : (<></>) }
                  </div>
                ))}
              </>
            )}
            <hr className="my-2 h-px bg-gray-300 border-0 w-full" />
          </div>
        </div>

        <div className="w-full h-12 px-2 py-2.5 bg-mode-light dark:bg-mode-dark">
          <div className="flex flex-wrap items-center justify-between mx-auto">
            <div className="flex items-center"></div>
            <div className="flex items-center md:order-2">
              <button type="button" className="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom" onClick={ () => { onOpenMenu() } }>
                <span className="sr-only">Open user menu</span>
                <img className="w-8 h-8 rounded-full" src={ globalState.auth.user.photo_karyawan} alt={ globalState.auth.user.nama_depan_karyawan } />
              </button>
              <div className={`absolute right-4 top-15 z-50 text-base list-none bg-mode-light dark:bg-mode-dark divide-y divide-gray-100 rounded shadow dark:divide-gray-600 ${ isOpen ? 'block' : 'hidden' }`} id="user-dropdown">
                <div className="px-4 py-3">
                  <span className="block text-sm text-gray-900 dark:text-white">{ globalState.auth.user.nama_depan_karyawan }</span>
                  <span className="block text-sm font-medium text-gray-500 truncate dark:text-gray-400">{ globalState.auth.user.email }</span>
                </div>
                <ul className="py-1" aria-labelledby="user-menu-button">
                  <li>
                    <a href="#" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profil</a>
                  </li>
                  <li>
                    <a href="#" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Pengaturan</a>
                  </li>
                  <li>
                    <a href="#" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Keluar</a>
                  </li>
                </ul>
              </div>
              <button data-collapse-toggle="mobile-menu-2" type="button" className="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                <span className="sr-only">Open main menu</span>
                <svg className="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fillRule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clipRule="evenodd"></path></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}

const Footer = () => {
  return (
    <>
      <footer className="sticky-footer">
        <div className="container my-auto">
            <div className="copyright text-center my-auto">
              <span></span>
            </div>
        </div>
      </footer>

      <a className="scroll-to-top rounded" href="#page-top">
          <i className="fas fa-angle-up"></i>
      </a>
    </>
  )
}

export { SideNavBar, Footer };