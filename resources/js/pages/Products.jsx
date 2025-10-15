import React, { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
import axios from '../bootstrap'

export default function Products() {
  const [items, setItems] = useState([])
  const [name, setName] = useState('')
  const [category, setCategory] = useState('')
  const [hasImage, setHasImage] = useState('')

  const load = async () => {
    const params = {}
    if (name) params.name = name
    if (category) params.category = category
    if (hasImage !== '') params.has_image = hasImage
    const { data } = await axios.get('/products', { params })
    const list = Array.isArray(data) ? data : (data.data ?? [])
    setItems(list)
  }

  useEffect(() => { load() }, [])

  return (
    <div className="space-y-4">
      <div className="flex flex-wrap items-end gap-3">
        <div>
          <label className="block text-sm">Nome</label>
          <input className="border rounded px-3 py-2" value={name} onChange={e=>setName(e.target.value)} />
        </div>
        <div>
          <label className="block text-sm">Categoria</label>
          <input className="border rounded px-3 py-2" value={category} onChange={e=>setCategory(e.target.value)} />
        </div>
        <div>
          <label className="block text-sm">Com imagem?</label>
          <select className="border rounded px-3 py-2" value={hasImage} onChange={e=>setHasImage(e.target.value)}>
            <option value="">Todos</option>
            <option value="1">Sim</option>
            <option value="0">Não</option>
          </select>
        </div>
        <button onClick={load} className="bg-gray-900 text-white px-4 py-2 rounded">Buscar</button>
        <Link to="/products/new" className="ml-auto bg-blue-600 text-white px-4 py-2 rounded">Novo produto</Link>
      </div>

      <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {items.map(p => (
          <div key={p.id} className="bg-white rounded-xl shadow p-4">
            {p.image_url ? (
              <img src={p.image_url} alt={p.name} className="w-full h-40 object-cover rounded" />
            ) : (
              <div className="w-full h-40 bg-gray-100 rounded grid place-items-center text-sm text-gray-500">Sem imagem</div>
            )}
            <div className="mt-2">
              <div className="font-medium">{p.name}</div>
              <div className="text-sm text-gray-600">R$ {Number(p.price).toFixed(2)}</div>
              <div className="text-xs text-gray-500">Categorias: {Array.isArray(p.categories) ? p.categories.join(', ') : '-'}</div>
            </div>
            <div className="mt-3 flex gap-2">
              <Link to={`/products/${p.id}`} className="text-blue-600 text-sm">Editar</Link>
              <button
                className="text-red-600 text-sm"
                onClick={async () => { await axios.delete(`/products/${p.id}`); await load() }}
              >Excluir</button>
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}
