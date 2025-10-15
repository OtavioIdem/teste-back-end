import React from 'react'
import { createRoot } from 'react-dom/client'
import { createBrowserRouter, RouterProvider, Link, Outlet, useNavigate } from 'react-router-dom'
import './bootstrap' // opcional, para axios base
import '../css/app.css'

import Login from './pages/Login'
import Products from './pages/Products'
import ProductForm from './pages/ProductForm'

function Layout() {
  const navigate = useNavigate()
  const logged = Boolean(localStorage.getItem('token'))
  const logout = () => {
    localStorage.removeItem('token')
    navigate('/login')
  }

  return (
    <div className="min-h-screen">
      <header className="bg-white border-b">
        <div className="max-w-6xl mx-auto px-4 h-14 flex items-center justify-between">
          <Link to="/" className="font-semibold">Library Admin</Link>
          <nav className="flex gap-4 text-sm">
            <Link to="/">Produtos</Link>
            {logged ? (
              <button onClick={logout} className="text-red-600">Sair</button>
            ) : (
              <Link to="/login" className="text-blue-600">Login</Link>
            )}
          </nav>
        </div>
      </header>
      <main className="max-w-6xl mx-auto px-4 py-6">
        <Outlet />
      </main>
    </div>
  )
}

const router = createBrowserRouter([
  { path: '/', element: <Layout />,
    children: [
      { index: true, element: <Products /> },
      { path: 'products', element: <Products /> }, 
      { path: 'products/new', element: <ProductForm mode="create" /> },
      { path: 'products/:id', element: <ProductForm mode="edit" /> },
    ]
  },
  { path: '/login', element: <Login /> },
])

createRoot(document.getElementById('app')).render(
  <React.StrictMode>
    <RouterProvider router={router} />
  </React.StrictMode>
)
