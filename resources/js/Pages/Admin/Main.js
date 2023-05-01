import { SideNavBar, Footer } from './Components/Partials';

const Main = () => {
  const burgerMenuHandlerClick = () => {
    //
  }

  const mainRender = () => {
    return 'This is an Main Template'
  }

  return (
    <>
      <div id="wrapper" className="h-screen">
        <header>
            <SideNavBar />
        </header>

        <main>
          { mainRender() }
        </main>
      </div>
      
      <Footer />
    </>
  );
}

export default Main;