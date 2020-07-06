import React from 'react'

export function Wrapper({ children }) {
  return (
    <div className='w-full md:w-1/2 xl:w-1/3 px-2 pt-2 mt-2'>
      <div className='flex border-b border-gray-600'>{children}</div>
    </div>
  )
}
