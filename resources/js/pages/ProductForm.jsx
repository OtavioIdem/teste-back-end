import React, { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import axios from '../bootstrap'

export default function ProductForm({ mode }) {
  const navigate = useNavigate()
  const { id } = useParams()
  const isEdit = mode === 'edit'

  const [name, setName] = useState('')
  const [price, setPrice] = useState('')
  const [description, setDescription] = useState('')
  const [category, setCategory] = useState('')
  const [image, setImage] = useState('')

  useEffect(() => {
    if (isEdit && id) {
      (async () => {
        const { data } = await axios.get(`/products/${id}`)
        setName(data.name)
        setPrice(String(data.price))
        setDescription(data.description)
        setImage(data.image_url || '')
        const firstCat = Array.isArray(data.categories) ? data.categories[0] : ''
        setCategory(firstCat || '')
      })()
    }
  }, [isEdit, id])

  const submit = async (e) => {
    e.preventDefault()
    const payload = { name, price: Number(price), description, category, image }
    if (isEdit && id) await axios.put(`/products/${id}`, payload)
    else await axios.post('/products', payload)
    navigate('/')
  }

  return (
    <div className="max-w-xl">
      <h1 className="text-xl font-semibold mb-4">{isEdit ? 'Editar produto' : 'Novo produto'}</h1>
      <form onSubmit={submit} className="bg-white rounded-xl shadow p-5 space-y-3">
        <div>
          <label className="block text-sm mb-1">Nome *</label>
          <input className="w-full border rounded px-3 py-2" value={name} onChange={e=>setName(e.target.value)} required/>
        </div>
        <div>
          <label className="block text-sm mb-1">Preço *</label>
          <input className="w-full border rounded px-3 py-2" type="number" step="0.01"
                 value={price} onChange={e=>setPrice(e.target.value)} required/>
        </div>
        <div>
          <label className="block text-sm mb-1">Descrição *</label>
          <textarea className="w-full border rounded px-3 py-2" rows={4}
                    value={description} onChange={e=>setDescription(e.target.value)} required/>
        </div>
        <div>
          <label className="block text-sm mb-1">Categoria *</label>
          <input className="w-full border rounded px-3 py-2" value={category} onChange={e=>setCategory(e.target.value)} required/>
          <p className="text-xs text-gray-500 mt-1">O backend aceita até 3; aqui usamos 1 string (como no enunciado).</p>
        </div>
        <div>
          <label className="block text-sm mb-1">URL da imagem</label>
          <input className="w-full border rounded px-3 py-2" value={image} onChange={e=>setImage(e.target.value)} />
        </div>
        <div className="pt-2 flex gap-2">
          <button className="bg-blue-600 text-white px-4 py-2 rounded">{isEdit ? 'Salvar' : 'Criar'}</button>
          <button type="button" onClick={() => navigate(-1)} className="px-4 py-2 rounded border">Cancelar</button>
        </div>
      </form>
    </div>
  )
}
