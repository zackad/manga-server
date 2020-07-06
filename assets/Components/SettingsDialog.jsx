export function SettingsDialog(props) {
  return (
    <div className='fixed inset-x-0 max-w-md top-12 mx-auto p-2 bg-gray-900 border rounded'>
      <h1 className='text-lg font-semibold'>Settings</h1>
      <div className={`mt-3`}>
        <label htmlFor='max-image-width'>
          Maximum image width <span className='text-sm text-gray-500'>(leave blank to default 100%)</span>
        </label>
        <input
          type='number'
          name='max-image-width'
          className='block w-full bg-primary text-gray-900 border py-1 px-2'
          placeholder='Value in pixel'
          value={props.value}
          onChange={props.onChange}
        />
      </div>
      <button className='mt-3 px-3 py-1 border rounded' onClick={props.onClick}>
        Close
      </button>
    </div>
  )
}
