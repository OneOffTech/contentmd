<div class="w-full h-full grid grid-cols-1 grid-rows-1 bg-gray-100 overflow-hidden">

    {{-- Decorative hatched boxes --}}
    <div class="col-start-1 row-start-1 grid grid-cols-5 h-full">
        <div class="col-start-2 w-52 h-52 hatch text-orange-500/40 dark:text-orange-400/40 pointer-events-none"></div>
        <div class="col-start-3 place-self-end w-52 h-52 hatch text-zinc-500/40 dark:text-zinc-400/40 pointer-events-none"></div>
        <div class="col-start-4 place-self-center w-52 h-52 line text-orange-500/40 dark:text-orange-400/40 pointer-events-none"></div>
    </div>

    <div class="col-start-1 row-start-1 grid grid-cols-5 h-full  ">
        <div style="font-size: 1.25rem;margin-top:8px;margin-left:12px;" class="text-zinc-500 font-mono leading-relaxed col-start-4 place-self-end col-span-2">
            <p class="block" style="color: #000;">---</p>
            <p  class="block"><span style="color: #000;display:inline-block;padding-right:0.5rem">title:</span> {{ $title ?? 'Introducing Content-md'}}</p>
            <p  class="block whitespace-nowrap text-nowrap"><span style="color: #000;display:inline-block;padding-right:0.5rem">description:</span> {{ $description ?? 'AI agents should be first-class visitors, let\'s give them a tailored experience.' }}</p>
            <p  class="block"><span style="color: #000;display:inline-block;padding-right:0.5rem">date:</span> {{ $date ?? '2026-04-29'}}</p>
            <p  class="block"><span style="color: #000;display:inline-block;padding-right:0.5rem">license:</span> {{ $licence ?? 'CC-BY-4.0'}}</p>
            <p class="block" style="color: #000;">---</p>
        </div>
    </div>

    {{-- Main content --}}
    <div style="padding:5rem;" class="col-start-1 row-start-1 flex flex-col p-20 gap-8">
        <div style="flex-grow: 1" class="flex flex-col justify-around gap-4">

            <div class="flex flex-col gap-4">


                <h1 class="font-mono font-bold text-black text-6xl m-0 leading-none">{{ $title ?? 'content-md'}}</h1>
                <p class="font-mono text-zinc-900 text-4xl text-balance m-0">{{ $description ?? 'open specification for high-fidelity textual representation'}}</p>
            </div>

        </div>

        <div style="width:400px;padding:1rem;" class="bg-orange-600 flex flex-row  items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"  fill="currentColor" style="color: #fff;height:40px">
                <path d="M0,52.67v-1.95h73.85v1.95H0ZM6.15,33.18v-1.95h67.69v1.95H6.15ZM6.15,48.78v-1.95h73.85v1.95H6.15ZM6.15,68.26v-1.95h12.31v1.95H6.15ZM6.15,72.16v-1.95h12.31v1.95H6.15ZM6.15,76.06v-1.95h12.31v1.95H6.15ZM12.31,29.27v-1.95h67.69v1.95H12.31ZM12.31,56.57v-1.95h12.31v1.95h-12.31ZM12.31,60.46v-1.95h12.31v1.95h-12.31ZM12.31,64.36v-1.95h12.31v1.95h-12.31ZM18.46,37.07v-1.95h12.3v1.95h-12.3ZM18.46,40.98v-1.95h12.3v1.95h-12.3ZM18.46,44.87v-1.95h12.3v1.95h-12.3ZM24.62,17.58v-1.95h12.3v1.95h-12.3ZM24.62,21.48v-1.95h12.3v1.95h-12.3ZM24.62,25.38v-1.95h12.3v1.95h-12.3ZM30.77,5.88v-1.95h12.31v1.95h-12.31ZM30.77,9.78v-1.95h12.31v1.95h-12.31ZM30.77,13.68v-1.95h12.31v1.95h-12.31ZM36.92,68.26v-1.95h12.31v1.95h-12.31ZM36.92,72.16v-1.95h12.31v1.95h-12.31ZM36.92,76.06v-1.95h12.31v1.95h-12.31ZM43.07,56.57v-1.95h12.31v1.95h-12.31ZM43.07,60.46v-1.95h12.31v1.95h-12.31ZM43.07,64.36v-1.95h12.31v1.95h-12.31ZM49.23,37.07v-1.95h12.31v1.95h-12.31ZM49.23,40.98v-1.95h12.31v1.95h-12.31ZM49.23,44.87v-1.95h12.31v1.95h-12.31ZM55.38,17.58v-1.95h12.31v1.95h-12.31ZM55.38,21.48v-1.95h12.31v1.95h-12.31ZM55.38,25.38v-1.95h12.31v1.95h-12.31ZM61.54,5.88v-1.95h12.31v1.95h-12.31ZM61.54,9.78v-1.95h12.31v1.95h-12.31ZM61.54,13.68v-1.95h12.31v1.95h-12.31Z"/>
            </svg>
            <h1 class="font-mono font-bold text-white text-4xl m-0 leading-none">content-md</h1>
        </div>
    </div>

</div>