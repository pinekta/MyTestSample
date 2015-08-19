" AUTHOR: pinekta <h03a081b@gmail.com>
" License: This file is placed in the public domain.

" if exists('g:loaded_previm') && g:loaded_previm
if exists('g:loaded_vim-reveal') && g:loaded_vim-reveal
  finish
endif
" let g:loaded_previm = 1
let g:loaded_vim-reveal = 1

let s:save_cpo = &cpo
set cpo&vim

function! s:setup_setting()
  " augroup Previm
  augroup vim-reveal
    autocmd! * <buffer>
    " if get(g:, "previm_enable_realtime", 0) ==# 1
    if get(g:, "vim-reveal_enable_realtime", 0) ==# 1
      " NOTE: It is too frequently in TextChanged/TextChangedI
      " autocmd CursorHold,CursorHoldI,InsertLeave,BufWritePost <buffer> call previm#refresh()
      autocmd CursorHold,CursorHoldI,InsertLeave,BufWritePost <buffer> call vim-reveal#refresh()
    else
      " autocmd BufWritePost <buffer> call previm#refresh()
      autocmd BufWritePost <buffer> call vim-reveal#refresh()
    endif
  augroup END

  " command! -buffer -nargs=0 PrevimOpen call previm#open(previm#make_preview_file_path('index.html'))
  command! -buffer -nargs=0 revealPreview call vim-reveal#open(vim-reveal#make_preview_file_path('index.html'))
endfunction

" augroup Previm
augroup vim-reveal
  autocmd!
  autocmd FileType *{md,mkd,markdown}* call <SID>setup_setting()
augroup END

let &cpo = s:save_cpo
unlet! s:save_cpo
