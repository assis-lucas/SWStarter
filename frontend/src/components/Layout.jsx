import { Link } from 'react-router-dom';
import { ChartBarIcon } from '@heroicons/react/24/outline';

const Layout = ({ children }) => {
  return (
    <div className="min-h-screen bg-gray-100">
      <nav className="bg-white border-b border-gray-300 shadow-sm">
        <div className="mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-center items-center h-16">
            <Link to="/" className="text-xl sm:text-2xl font-bold text-primary-600 hover:text-primary-800 transition-colors">
              SWStarter
            </Link>
            <Link
              to="/stats"
              className="flex ml-auto items-center gap-1 sm:gap-2 px-2 sm:px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-lg transition-colors"
            >
              <ChartBarIcon className="w-5 h-5" />
              <span className="hidden sm:inline">Statistics</span>
            </Link>
          </div>
        </div>
      </nav>
      <main className="mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
        {children}
      </main>
    </div>
  );
};

export default Layout;
