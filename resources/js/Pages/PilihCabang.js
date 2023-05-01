import { useState, useEffect } from 'react'
import { Head } from '@inertiajs/inertia-react'
import axios from 'axios'
import ContentLoader from "react-content-loader"
import { replaceURI, loadPage } from '../Pages/Admin/Components/Helpers'
import { Translations as t } from '../Mixins/translations'
import { useGlobalState } from '@/Components/AppContext'
import Main from './Admin/Main'
import styles from '../app.module.css'

function SwitchBranch() {
  replaceURI('/')
  const { globalState, setGlobalState } = useGlobalState()
  const [branchList, setBranchList] = useState({})
  const [isLoading, setIsLoading] = useState(true)

  const handleStoreClick = async (storeName) => {
    replaceURI('/dashboard')
    setGlobalState((prevProps) => ({
      ...prevProps,
      isDashboardPage: true,
      store: storeName,
      page: 'dashboard'
    }))
  }

  useEffect(() => {
    const fetchData = async () => {
      const response = await loadPage(`https://account.alkaysan.co.id/api/v1/branch?accessby=${ globalState.auth.user.id_karyawan }&response_type=json`)
      if (response.message === 'success') {
        setBranchList(response.data)
        setIsLoading(false)
      }
    }

    fetchData()
  }, [])

  return (
    <>
      { globalState.isDashboardPage ? (
        <Main />
      ) : (
        <>
          <Head title={ t('Select Branch') } />
          <div className={ [styles.relative, styles.flex, styles['items-top'], styles['justify-center'], styles['h-auto'], styles['sm:items-center'], styles['sm:pt-0']].join(' ') }>
            <div className={ [styles['max-w-6xl'], styles['mx-auto'], styles['sm:px-6'], styles['lg:px-8']].join(' ') }>
              <div className={ [styles.container, styles.flex, styles['flex-col'], styles['items-center'], styles['justify-center'], styles['my-8']].join(' ') }>
                <h1 className={ [styles['mb-0'], styles['text-3xl'], styles['text-center'], styles['text-black'], styles['dark:text-white']].join(' ') }>{ t('Select Branch') }</h1>
                <ul className={ styles.list__group }>
                  { isLoading ? (
                    <ContentLoader 
                      speed={2}
                      width={400}
                      height={50}
                      viewBox="0 0 400 50"
                      backgroundColor="#d9d9d9"
                      foregroundColor="#ededed"
                    >
                      <rect x="0" y="0" rx="0" ry="0" width="100%" height="50"/>
                    </ContentLoader>
                  ) : (
                    <>
                      { branchList.map((item, index) => (
                        <li className={ styles.list__group__item } key={ index }>
                          <button onClick={ () => { handleStoreClick(item.folder.toLowerCase()) } }>
                            <div className={ styles['text-center'] }>
                              <div className={ [styles['ml-2'], styles['center']].join(' ') }>
                                <h3 className={ [styles['text-black'], styles['font-semibold']].join(' ') }>{ item.nama_cabang }</h3>
                              </div>
                            </div>
                          </button>
                        </li>
                      ))}
                    </>
                  )}
                </ul>
              </div>
            </div>
          </div>
        </>
      )}
    </>
  )
}

export default SwitchBranch;
