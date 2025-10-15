import React, { useState } from 'react'
import axios from '../bootstrap'
import { useNavigate } from 'react-router-dom'

export default function Login() {
  const navigate = useNavigate()
  const [email, setEmail] = useState('admin@example.com')
  const [password, setPassword] = useState('password')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const submit = async (e) => {
    e.preventDefault()
    setError(null); setLoading(true)
    try {
      const { data } = await axios.post('/auth/login', { email, password })
      localStorage.setItem('token', data.token)
      navigate('/')
    } catch (err) {
      setError(err?.response?.data?.message || 'Falha no login')
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="max-w-sm mx-auto bg-white p-6 rounded-xl shadow">
      <h1 className="text-xl font-semibold mb-4">Entrar</h1>
      {error && <div className="mb-3 text-sm text-red-600">{error}</div>}
      <form onSubmit={submit} className="space-y-3">
        <div>
          <label className="block text-sm mb-1">Email</label>
          <input className="w-full border rounded px-3 py-2" type="email"
                 value={email} onChange={e=>setEmail(e.target.value)} required/>
        </div>
        <div>
          <label className="block text-sm mb-1">Senha</label>
          <input className="w-full border rounded px-3 py-2" type="password"
                 value={password} onChange={e=>setPassword(e.target.value)} required/>
        </div>
        <button disabled={loading} className="w-full bg-blue-600 text-white rounded py-2">
          {loading ? 'Entrando...' : 'Entrar'}
        </button>
      </form>
    </div>
  )
}
