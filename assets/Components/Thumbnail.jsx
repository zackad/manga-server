import { h } from 'preact'

export function Thumbnail({ image }) {
  return (
    <div className='p-1'>
      <img src={image} alt='Cover Thumbnail' className='w-full' />
    </div>
  )
}
